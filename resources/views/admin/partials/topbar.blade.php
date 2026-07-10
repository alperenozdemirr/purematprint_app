<header class="sticky top-0 z-[30] flex h-16 items-center gap-3 border-b border-ink/10 bg-surface/95 px-4 shadow-sm backdrop-blur-sm lg:px-8">
  {{-- Mobil menü aç --}}
  <button type="button" data-admin-nav-open aria-label="Menüyü aç"
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover lg:hidden">
    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
  </button>

  {{-- Sayfa başlığı --}}
  <div class="min-w-0 flex-1">
    <h1 class="truncate font-heading text-[19px] font-bold leading-tight tracking-tight text-ink lg:text-[22px]">@yield('page_title', 'Dashboard')</h1>
    @hasSection('breadcrumb')
      <p class="truncate font-body text-[12px] font-medium text-muted">@yield('breadcrumb')</p>
    @endif
  </div>

  {{-- Arama --}}
  <form action="#" method="get" class="hidden items-center overflow-hidden rounded-lg border border-ink/10 bg-cream md:flex">
    <input type="search" name="q" placeholder="Ara..." aria-label="Ara"
           class="w-48 border-0 bg-transparent px-3.5 py-2 font-body text-[14px] text-ink outline-none placeholder:text-muted lg:w-56">
    <button type="submit" aria-label="Ara" class="flex h-10 w-10 shrink-0 items-center justify-center bg-accent text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="7.8"/><path stroke-linecap="square" d="m17 17 4.5 4.5"/></svg>
    </button>
  </form>

  {{-- Bildirim --}}
  <button type="button" aria-label="Bildirimler"
          class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
    <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-badge-sale px-1 font-body text-[10px] font-bold leading-none text-on-dark">3</span>
  </button>

  {{-- Admin --}}
  <div class="flex shrink-0 items-center gap-2">
    <div class="flex items-center gap-2.5 rounded-lg bg-cream px-2.5 py-1.5">
      <span class="flex h-8 w-8 items-center justify-center rounded-md bg-accent font-body text-[13px] font-bold text-on-dark">
        {{ strtoupper(substr(optional(auth()->user())->name ?? 'A', 0, 1)) }}
      </span>
      <span class="hidden text-left sm:block">
        <span class="block font-body text-[13px] font-bold leading-tight text-ink">{{ optional(auth()->user())->name ?? 'Yönetici' }}</span>
        <span class="block font-body text-[11px] leading-tight text-muted">Admin</span>
      </span>
    </div>
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" title="Çıkış Yap" aria-label="Çıkış Yap"
              class="flex h-10 w-10 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      </button>
    </form>
  </div>
</header>
