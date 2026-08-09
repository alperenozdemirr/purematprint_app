@php
  $adminSidebarCollapsed = (bool) session('admin_sidebar_collapsed', false);
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="favicon.avif" type="image/avif">
  <title>@yield('title', 'Yönetim') — PureMatPrint Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            bg: '#faf6ee',
            surface: '#fffdf8',
            cream: '#fbf8f1',
            ink: '#1a1a1a',
            muted: '#5e5a54',
            accent: '#354e9c',
            'accent-dark': '#283c78',
            action: '#5a544e',
            'action-hover': '#6b645c',
            'action-muted': '#8a837a',
            'on-dark': '#faf6ee',
            badge: '#e8e1d4',
            'badge-fg': '#5a544e',
            'badge-sale': '#c4522a',
            dark: '#2a2826',
            hover: '#f2ece3',
            success: '#2f7a4d',
            danger: '#b61d0f',
            warning: '#c4772a',
          },
          fontFamily: {
            heading: ['Petrona', 'Georgia', 'Times New Roman', 'serif'],
            body: ['IBM Plex Sans', 'system-ui', '-apple-system', 'sans-serif'],
          },
          maxWidth: { admin: '1440px' },
          boxShadow: {
            brutal: '6px 6px 0 rgba(26, 26, 26, 0.68)',
            'brutal-sm': '4px 4px 0 rgba(26, 26, 26, 0.68)',
            ui: '4px 4px 0 rgba(90, 84, 78, 0.2)',
            'ui-sm': '3px 3px 0 rgba(90, 84, 78, 0.16)',
            card: '0 1px 2px rgba(26,26,26,0.04), 0 4px 16px rgba(26,26,26,0.06)',
            'card-md': '0 2px 8px rgba(26,26,26,0.06), 0 8px 24px rgba(26,26,26,0.05)',
          },
        },
      },
    };
  </script>
  <style>
    html { scroll-behavior: smooth; box-sizing: border-box; }
    *, *::before, *::after { box-sizing: inherit; }
    img { max-width: 100%; height: auto; display: block; }
    body.admin-nav-open { overflow: hidden; }
    @media (min-width: 1024px) { body.admin-nav-open { overflow: auto; } }

    .admin-sidebar {
      transition: width .28s cubic-bezier(.22, 1, .36, 1), transform .28s cubic-bezier(.22, 1, .36, 1);
    }

    .admin-shell {
      transition: padding-left .28s cubic-bezier(.22, 1, .36, 1);
    }

    .admin-sidebar-nav {
      scrollbar-width: thin;
      scrollbar-color: rgba(250, 246, 238, 0.22) transparent;
    }

    .admin-sidebar-nav::-webkit-scrollbar {
      width: 3px;
    }

    .admin-sidebar-nav::-webkit-scrollbar-track {
      background: transparent;
      margin: 8px 0;
    }

    .admin-sidebar-nav::-webkit-scrollbar-thumb {
      background: rgba(250, 246, 238, 0.22);
      border-radius: 999px;
    }

    .admin-sidebar-nav::-webkit-scrollbar-thumb:hover {
      background: rgba(250, 246, 238, 0.4);
    }

    .admin-sidebar-nav::-webkit-scrollbar-button {
      display: none;
      width: 0;
      height: 0;
    }

    @media (min-width: 1024px) {
      body.admin-sidebar-collapsed .admin-sidebar {
        width: 76px;
      }

      body.admin-sidebar-collapsed .admin-shell {
        padding-left: 76px;
      }

      body.admin-sidebar-collapsed .admin-nav-text {
        display: none !important;
      }

      body.admin-sidebar-collapsed .admin-sidebar-header {
        position: relative;
        justify-content: center;
        gap: 0;
        padding-left: .5rem;
        padding-right: .5rem;
      }

      body.admin-sidebar-collapsed .admin-sidebar-header [data-admin-sidebar-toggle] {
        position: static;
      }

      body.admin-sidebar-collapsed .admin-sidebar-brand {
        display: none;
      }

      body.admin-sidebar-collapsed .admin-nav-link {
        justify-content: center;
        gap: 0;
        padding-left: .55rem;
        padding-right: .55rem;
      }

      body.admin-sidebar-collapsed .admin-nav-label {
        height: 1px;
        margin: .7rem .85rem;
        padding: 0 !important;
        overflow: hidden;
        color: transparent;
        font-size: 0;
        line-height: 0;
        background: rgba(250, 246, 238, 0.12);
        border: 0;
      }

      body.admin-sidebar-collapsed .admin-sidebar-footer {
        padding: .5rem;
      }

      body.admin-sidebar-collapsed .admin-footer-btn {
        padding-left: .55rem;
        padding-right: .55rem;
      }

      body.admin-sidebar-collapsed [data-admin-sidebar-toggle-icon] {
        transform: rotate(180deg);
      }
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Petrona:wght@500;600;700&display=swap">
</head>
<body class="font-body text-base leading-[1.55] text-ink bg-bg antialiased min-h-dvh {{ $adminSidebarCollapsed ? 'admin-sidebar-collapsed' : '' }}"
      data-admin-sidebar-collapsed="{{ $adminSidebarCollapsed ? '1' : '0' }}">

  {{-- Sol menü --}}
  @include('admin.partials.sidebar')

  {{-- Mobil menü overlay --}}
  <div class="fixed inset-0 z-[40] bg-ink/60 opacity-0 invisible transition-[opacity,visibility] duration-300 lg:hidden [&.open]:opacity-100 [&.open]:visible" data-admin-overlay></div>

  {{-- İçerik alanı --}}
  <div class="admin-shell lg:pl-[264px] min-h-dvh flex flex-col">
    @include('admin.partials.topbar')

    <main class="flex-1 w-full max-w-admin mx-auto px-5 py-6 lg:px-8 lg:py-8">
      @if (session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-success/20 bg-success/5 px-4 py-3.5 shadow-card">
          <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-success text-on-dark">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
          </span>
          <p class="text-sm font-medium text-ink">{{ session('success') }}</p>
        </div>
      @endif
      @if (session('error'))
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-danger/20 bg-danger/5 px-4 py-3.5 shadow-card">
          <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-danger text-on-dark">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
          </span>
          <p class="text-sm font-medium text-ink">{{ session('error') }}</p>
        </div>
      @endif

      @yield('content')
    </main>

    <footer class="mt-auto border-t border-ink/12 px-5 py-5 lg:px-8">
      <p class="text-xs text-muted">&copy; {{ date('Y') }} PureMatPrint — Yönetim Paneli</p>
    </footer>
  </div>

  <script>
    (function () {
      const body = document.body;
      const overlay = document.querySelector('[data-admin-overlay]');
      const sidebar = document.querySelector('[data-admin-sidebar]');
      const openBtns = document.querySelectorAll('[data-admin-nav-open]');
      const closeEls = document.querySelectorAll('[data-admin-nav-close]');
      const toggleBtns = document.querySelectorAll('[data-admin-sidebar-toggle]');
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const persistUrl = @json(route('admin.sidebarToggle'));

      const openMobile = () => {
        sidebar?.classList.add('open');
        overlay?.classList.add('open');
        body.classList.add('admin-nav-open');
      };

      const closeMobile = () => {
        sidebar?.classList.remove('open');
        overlay?.classList.remove('open');
        body.classList.remove('admin-nav-open');
      };

      const setCollapsed = (collapsed, persist = true) => {
        body.classList.toggle('admin-sidebar-collapsed', collapsed);
        body.dataset.adminSidebarCollapsed = collapsed ? '1' : '0';

        toggleBtns.forEach((btn) => {
          btn.setAttribute('aria-label', collapsed ? 'Menüyü genişlet' : 'Menüyü daralt');
          btn.setAttribute('title', collapsed ? 'Menüyü genişlet' : 'Menüyü daralt');
        });

        if (!persist) {
          return;
        }

        fetch(persistUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({ collapsed }),
          credentials: 'same-origin',
        }).catch(() => {});
      };

      openBtns.forEach((b) => b.addEventListener('click', openMobile));
      closeEls.forEach((b) => b.addEventListener('click', closeMobile));
      if (overlay) overlay.addEventListener('click', closeMobile);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMobile();
      });

      toggleBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
          const next = !body.classList.contains('admin-sidebar-collapsed');
          setCollapsed(next, true);
        });
      });

      // Session'dan gelen durum zaten body class'ında; ikon etiketlerini senkronize et
      setCollapsed(body.classList.contains('admin-sidebar-collapsed'), false);
    })();

    (function () {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const searchRoot = document.querySelector('[data-admin-search]');
      const searchInput = searchRoot?.querySelector('[data-admin-search-input]');
      const searchResults = searchRoot?.querySelector('[data-admin-search-results]');
      const searchUrl = @json(route('admin.search'));
      let searchTimer = null;

      const escapeHtml = (value) => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');

      const renderGroup = (title, items) => {
        if (!items.length) return '';
        return `
          <div class="border-b border-ink/8 last:border-b-0">
            <p class="px-3 pt-3 pb-1 font-body text-[10px] font-bold uppercase tracking-[0.08em] text-muted">${escapeHtml(title)}</p>
            ${items.map((item) => `
              <a href="${escapeHtml(item.url)}" class="block px-3 py-2.5 hover:bg-cream">
                <span class="block font-body text-[13px] font-semibold text-ink">${escapeHtml(item.label)}</span>
                <span class="block font-body text-[11px] text-muted">${escapeHtml(item.meta || '')}</span>
              </a>
            `).join('')}
          </div>
        `;
      };

      const runSearch = async (q) => {
        if (!searchResults) return;
        if (q.trim().length < 2) {
          searchResults.classList.add('hidden');
          searchResults.innerHTML = '';
          return;
        }

        try {
          const res = await fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
          });
          if (!res.ok) throw new Error('search failed');
          const data = await res.json();
          const html = [
            renderGroup('Siparişler', data.orders || []),
            renderGroup('Müşteriler', data.customers || []),
            renderGroup('Ürünler', data.products || []),
          ].join('');

          if (!html) {
            searchResults.innerHTML = '<p class="px-4 py-6 text-center font-body text-[13px] text-muted">Sonuç bulunamadı.</p>';
          } else {
            searchResults.innerHTML = html;
          }
          searchResults.classList.remove('hidden');
        } catch (e) {
          searchResults.innerHTML = '<p class="px-4 py-6 text-center font-body text-[13px] text-danger">Arama yapılamadı.</p>';
          searchResults.classList.remove('hidden');
        }
      };

      searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => runSearch(searchInput.value || ''), 250);
      });

      searchRoot?.querySelector('[data-admin-search-form]')?.addEventListener('submit', (event) => {
        event.preventDefault();
        runSearch(searchInput?.value || '');
      });

      const notifRoot = document.querySelector('[data-admin-notifications]');
      const notifToggle = notifRoot?.querySelector('[data-admin-notifications-toggle]');
      const notifPanel = notifRoot?.querySelector('[data-admin-notifications-panel]');

      const closeSearch = () => searchResults?.classList.add('hidden');
      const closeNotif = () => {
        notifPanel?.classList.add('hidden');
        notifToggle?.setAttribute('aria-expanded', 'false');
      };

      notifToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const willOpen = notifPanel?.classList.contains('hidden');
        closeSearch();
        if (willOpen) {
          notifPanel?.classList.remove('hidden');
          notifToggle?.setAttribute('aria-expanded', 'true');
        } else {
          closeNotif();
        }
      });

      document.addEventListener('click', (event) => {
        if (searchRoot && !searchRoot.contains(event.target)) closeSearch();
        if (notifRoot && !notifRoot.contains(event.target)) closeNotif();
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          closeSearch();
          closeNotif();
        }
      });
    })();
  </script>
  @yield('scripts')
  @stack('scripts')
</body>
</html>
