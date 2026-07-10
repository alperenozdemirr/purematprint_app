<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Petrona:wght@500;600;700&display=swap">
</head>
<body class="font-body text-base leading-[1.55] text-ink bg-bg antialiased min-h-dvh">

  {{-- Sol menü --}}
  @include('admin.partials.sidebar')

  {{-- Mobil menü overlay --}}
  <div class="fixed inset-0 z-[40] bg-ink/60 opacity-0 invisible transition-[opacity,visibility] duration-300 lg:hidden [&.open]:opacity-100 [&.open]:visible" data-admin-overlay></div>

  {{-- İçerik alanı --}}
  <div class="lg:pl-[264px] min-h-dvh flex flex-col">
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

      const open = () => { sidebar.classList.add('open'); overlay.classList.add('open'); body.classList.add('admin-nav-open'); };
      const close = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); body.classList.remove('admin-nav-open'); };

      openBtns.forEach((b) => b.addEventListener('click', open));
      closeEls.forEach((b) => b.addEventListener('click', close));
      if (overlay) overlay.addEventListener('click', close);
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    })();
  </script>
  @yield('scripts')
</body>
</html>
