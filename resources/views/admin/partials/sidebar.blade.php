@php
    $navLink = 'group flex items-center gap-3 px-4 py-2.5 font-body text-[14px] font-semibold text-on-dark/75 border-2 border-transparent transition-colors hover:bg-white/[0.06] hover:text-on-dark';
    $navLinkActive = 'bg-accent text-on-dark border-ink/40 shadow-[3px_3px_0_rgba(0,0,0,0.35)] hover:bg-accent hover:text-on-dark';
    $navLabel = 'px-4 pt-6 pb-2 font-body text-[11px] font-bold uppercase tracking-[0.14em] text-on-dark/40';
@endphp

<aside data-admin-sidebar
       class="fixed top-0 left-0 z-[50] flex h-dvh w-[264px] -translate-x-full flex-col border-r-[3px] border-ink bg-dark transition-transform duration-300 lg:translate-x-0 [&.open]:translate-x-0">

  {{-- Logo --}}
  <div class="flex h-16 shrink-0 items-center justify-between border-b-[3px] border-ink px-5">
    <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5">
      <img src="{{ asset('shared_directory') }}/logo.avif" alt="PureMatPrint" class="h-6 w-auto brightness-0 invert">
      <span class="font-heading text-[13px] font-bold uppercase tracking-[0.12em] text-on-dark/60">Admin</span>
    </a>
    <button type="button" data-admin-nav-close aria-label="Menüyü kapat"
            class="flex h-9 w-9 items-center justify-center border-2 border-on-dark/25 text-on-dark hover:bg-white/10 lg:hidden">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
  </div>

  {{-- Menü --}}
  <nav class="flex-1 overflow-y-auto px-3 pb-6">
    <p class="{{ $navLabel }}">Genel</p>
    <a href="{{ route('admin.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.index') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/></svg>
      <span>Dashboard</span>
    </a>

    <p class="{{ $navLabel }}">Katalog</p>
    <a href="{{ route('admin.productList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.product*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7 12 3 4 7v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 11v10"/></svg>
      <span>Ürünler</span>
    </a>
    <a href="{{ route('admin.categoryList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.category*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5h18M3 12h18M3 19h18"/></svg>
      <span>Kategoriler</span>
    </a>
    <a href="{{ route('admin.collectionList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.collection*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
      <span>Koleksiyonlar</span>
    </a>
    <a href="{{ route('admin.bannerList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.banner*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5h18v14H3zM3 9h18"/></svg>
      <span>Bannerlar</span>
    </a>

    <p class="{{ $navLabel }}">Satış</p>
    <a href="{{ route('admin.orderList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.order*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
      <span>Siparişler</span>
    </a>
    <a href="{{ route('admin.commentList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.comment*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span>Yorumlar</span>
    </a>

    <p class="{{ $navLabel }}">İçerik</p>
    <a href="{{ route('admin.blogList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.blog*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></svg>
      <span>Blog</span>
    </a>

    <p class="{{ $navLabel }}">Sistem</p>
    <a href="{{ route('admin.settings') }}" class="{{ $navLink }} {{ request()->routeIs('admin.settings*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.6.77 1.05 1.4 1.2H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      <span>Ayarlar</span>
    </a>
    <a href="{{ route('admin.userList') }}" class="{{ $navLink }} {{ request()->routeIs('admin.user*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <span>Kullanıcılar</span>
    </a>
  </nav>

  {{-- Siteye dön & çıkış --}}
  <div class="shrink-0 border-t-[3px] border-ink p-3 space-y-2">
    <a href="{{ route('index') }}" target="_blank"
       class="flex items-center justify-center gap-2 border-2 border-on-dark/25 px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-white/10">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
      <span>Siteyi Görüntüle</span>
    </a>
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit"
              class="flex w-full items-center justify-center gap-2 border-2 border-on-dark/25 px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-white/10">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        <span>Çıkış Yap</span>
      </button>
    </form>
  </div>
</aside>
