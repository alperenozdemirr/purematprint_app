<header class="sticky top-0 z-[30] flex h-16 items-center gap-3 border-b border-ink/10 bg-surface/95 px-4 shadow-sm backdrop-blur-sm lg:px-8">
  {{-- Mobil menü aç --}}
  <button type="button" data-admin-nav-open aria-label="Menüyü aç"
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover lg:hidden">
    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
  </button>

  {{-- Masaüstü menü daralt / genişlet --}}
  <button type="button" data-admin-sidebar-toggle aria-label="Menüyü daralt" title="Menüyü daralt / genişlet"
          class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover lg:flex">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M4 6h16M4 12h10M4 18h16"/>
      <path d="m14 9 3 3-3 3"/>
    </svg>
  </button>

  {{-- Sayfa başlığı --}}
  <div class="min-w-0 flex-1">
    <h1 class="truncate font-heading text-[19px] font-bold leading-tight tracking-tight text-ink lg:text-[22px]">@yield('page_title', 'Dashboard')</h1>
    @hasSection('breadcrumb')
      <p class="truncate font-body text-[12px] font-medium text-muted">@yield('breadcrumb')</p>
    @endif
  </div>

  {{-- Arama --}}
  <div class="relative hidden md:block" data-admin-search>
    <form action="{{ route('admin.search') }}" method="get" class="flex items-center overflow-hidden rounded-lg border border-ink/10 bg-cream" data-admin-search-form>
      <input type="search" name="q" placeholder="Sipariş, müşteri, ürün..." aria-label="Ara" autocomplete="off"
             data-admin-search-input
             class="w-48 border-0 bg-transparent px-3.5 py-2 font-body text-[14px] text-ink outline-none placeholder:text-muted lg:w-64">
      <button type="submit" aria-label="Ara" class="flex h-10 w-10 shrink-0 items-center justify-center bg-accent text-on-dark transition-colors hover:bg-accent-dark">
        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="7.8"/><path stroke-linecap="square" d="m17 17 4.5 4.5"/></svg>
      </button>
    </form>
    <div class="absolute right-0 top-[calc(100%+8px)] z-40 hidden w-[360px] overflow-hidden rounded-xl border border-ink/10 bg-surface shadow-card-md" data-admin-search-results></div>
  </div>

  {{-- Bildirim --}}
  <div class="relative" data-admin-notifications>
    <button type="button" aria-label="Bildirimler" aria-expanded="false" data-admin-notifications-toggle
            class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
      @php $unreadCount = (int) ($adminUnreadNotificationCount ?? 0); @endphp
      <span class="absolute -right-1 -top-1 {{ $unreadCount > 0 ? 'flex' : 'hidden' }} h-5 min-w-5 items-center justify-center rounded-full bg-badge-sale px-1 font-body text-[10px] font-bold leading-none text-on-dark"
            data-admin-notifications-count>{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
    </button>

    <div class="absolute right-0 top-[calc(100%+8px)] z-40 hidden w-[360px] overflow-hidden rounded-xl border border-ink/10 bg-surface shadow-card-md" data-admin-notifications-panel>
      <div class="flex items-center justify-between border-b border-ink/10 px-4 py-3">
        <p class="font-heading text-[14px] font-bold text-ink m-0">Bildirimler</p>
        <form action="{{ route('admin.notificationMarkAllRead') }}" method="POST">
          @csrf
          <button type="submit" class="font-body text-[11px] font-bold uppercase tracking-[0.04em] text-accent hover:underline">Tümünü okundu yap</button>
        </form>
      </div>
      <ul class="max-h-[360px] overflow-y-auto divide-y divide-ink/8" data-admin-notifications-list>
        @forelse (($adminRecentNotifications ?? collect()) as $notification)
          <li>
            <a href="{{ route('admin.notificationOpen', $notification->id) }}"
               class="block px-4 py-3 transition-colors hover:bg-cream {{ $notification->isRead() ? 'opacity-70' : 'bg-accent/5' }}">
              <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-accent m-0">{{ $notification->type?->label() }}</p>
              <p class="mt-1 font-body text-[13px] font-semibold text-ink m-0">{{ $notification->title }}</p>
              @if (filled($notification->body))
                <p class="mt-1 font-body text-[12px] text-muted m-0 line-clamp-2">{{ $notification->body }}</p>
              @endif
              <p class="mt-1 font-body text-[11px] text-muted m-0">{{ $notification->created_at?->diffForHumans() }}</p>
            </a>
          </li>
        @empty
          <li class="px-4 py-8 text-center font-body text-[13px] text-muted">Henüz bildirim yok.</li>
        @endforelse
      </ul>
      <div class="border-t border-ink/10 px-4 py-3">
        <a href="{{ route('admin.notificationList') }}" class="font-body text-[12px] font-bold uppercase tracking-[0.04em] text-ink hover:text-accent">Tüm bildirimleri gör</a>
      </div>
    </div>
  </div>

  {{-- Admin --}}
  <div class="flex shrink-0 items-center gap-2">
    <a href="{{ route('admin.account') }}" class="flex items-center gap-2.5 rounded-lg bg-cream px-2.5 py-1.5 transition-colors hover:bg-hover" title="Hesabım">
      <span class="flex h-8 w-8 items-center justify-center rounded-md bg-accent font-body text-[13px] font-bold text-on-dark">
        {{ strtoupper(substr(optional(auth('admin')->user())->name ?? 'A', 0, 1)) }}
      </span>
      <span class="hidden text-left sm:block">
        <span class="block font-body text-[13px] font-bold leading-tight text-ink">{{ optional(auth('admin')->user())->name ?? 'Yönetici' }}</span>
        <span class="block font-body text-[11px] leading-tight text-muted">Admin</span>
      </span>
    </a>
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" title="Çıkış Yap" aria-label="Çıkış Yap"
              class="flex h-10 w-10 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      </button>
    </form>
  </div>
</header>
