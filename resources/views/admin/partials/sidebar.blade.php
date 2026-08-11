@php
    $navLink = 'admin-nav-link group flex items-center gap-3 px-3 py-2.5 font-body text-[14px] font-semibold text-on-dark/75 border-2 border-transparent transition-colors hover:bg-white/[0.06] hover:text-on-dark';
    $navLinkActive = 'bg-accent text-on-dark border-ink/40 shadow-[3px_3px_0_rgba(0,0,0,0.35)] hover:bg-accent hover:text-on-dark';
    $navLabel = 'admin-nav-label px-3 pt-5 pb-2 font-body text-[11px] font-bold uppercase tracking-[0.14em] text-on-dark/40';
@endphp

<aside data-admin-sidebar
       class="admin-sidebar fixed top-0 left-0 z-[50] flex h-dvh w-[264px] -translate-x-full flex-col border-r-[3px] border-ink bg-dark lg:translate-x-0 [&.open]:translate-x-0">

  {{-- Logo --}}
  <div class="admin-sidebar-header flex h-16 shrink-0 items-center justify-between border-b-[3px] border-ink px-3">
    <a href="{{ route('admin.index') }}" class="admin-sidebar-brand flex min-w-0 items-center gap-2.5" title="Dashboard">
      @include('user.partials.site-logo', ['class' => 'h-6 w-auto shrink-0', 'invertOnDark' => true])
      <span class="admin-nav-text font-heading text-[13px] font-bold uppercase tracking-[0.12em] text-on-dark/60">Admin</span>
    </a>
    <button type="button" data-admin-nav-close aria-label="Menüyü kapat"
            class="flex h-9 w-9 shrink-0 items-center justify-center border-2 border-on-dark/25 text-on-dark hover:bg-white/10 lg:hidden">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
    <button type="button" data-admin-sidebar-toggle aria-label="Menüyü daralt" title="Menüyü daralt / genişlet"
            class="hidden h-9 w-9 shrink-0 items-center justify-center border-2 border-on-dark/25 text-on-dark transition-colors hover:bg-white/10 lg:flex">
      <svg data-admin-sidebar-toggle-icon width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M15 18 9 12l6-6"/>
      </svg>
    </button>
  </div>

  {{-- Menü --}}
  <nav class="admin-sidebar-nav flex-1 overflow-y-auto px-2 pb-6">
    <p class="{{ $navLabel }}">Genel</p>
    <a href="{{ route('admin.index') }}" title="Dashboard" class="{{ $navLink }} {{ request()->routeIs('admin.index') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/></svg>
      <span class="admin-nav-text">Dashboard</span>
    </a>

    <p class="{{ $navLabel }}">Katalog</p>
    <a href="{{ route('admin.productList') }}" title="Ürünler" class="{{ $navLink }} {{ request()->routeIs('admin.product*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7 12 3 4 7v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 11v10"/></svg>
      <span class="admin-nav-text">Ürünler</span>
    </a>
    <a href="{{ route('admin.categoryList') }}" title="Kategoriler" class="{{ $navLink }} {{ request()->routeIs('admin.category*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5h18M3 12h18M3 19h18"/></svg>
      <span class="admin-nav-text">Kategoriler</span>
    </a>
    <a href="{{ route('admin.collectionList') }}" title="Koleksiyonlar" class="{{ $navLink }} {{ request()->routeIs('admin.collection*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
      <span class="admin-nav-text">Koleksiyonlar</span>
    </a>
    <a href="{{ route('admin.bannerList') }}" title="Bannerlar" class="{{ $navLink }} {{ request()->routeIs('admin.banner*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5h18v14H3zM3 9h18"/></svg>
      <span class="admin-nav-text">Bannerlar</span>
    </a>

    <p class="{{ $navLabel }}">Satış</p>
    <a href="{{ route('admin.orderList') }}" title="Siparişler" class="{{ $navLink }} {{ request()->routeIs('admin.order*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
      <span class="admin-nav-text">Siparişler</span>
    </a>
    <a href="{{ route('admin.notificationList') }}" title="Bildirimler" class="{{ $navLink }} {{ request()->routeIs('admin.notification*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
      <span class="admin-nav-text">Bildirimler</span>
      @if (($adminUnreadNotificationCount ?? 0) > 0)
        <span class="admin-nav-text ml-auto inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-badge-sale px-1.5 font-body text-[10px] font-bold text-on-dark">{{ $adminUnreadNotificationCount > 99 ? '99+' : $adminUnreadNotificationCount }}</span>
      @endif
    </a>
    <a href="{{ route('admin.analytics') }}" title="Günlük Analiz" class="{{ $navLink }} {{ request()->routeIs('admin.analytics*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19V5M8 19v-7M12 19V9M16 19v-4M20 19V7"/></svg>
      <span class="admin-nav-text">Günlük Analiz</span>
    </a>
    <a href="{{ route('admin.shipinkSettings') }}" title="Shipink Ayarları" class="{{ $navLink }} {{ request()->routeIs('admin.shipinkSettings*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span class="admin-nav-text">Shipink Ayarları</span>
    </a>
    <a href="{{ route('admin.commentList') }}" title="Yorumlar" class="{{ $navLink }} {{ request()->routeIs('admin.comment*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="admin-nav-text">Yorumlar</span>
    </a>

    <p class="{{ $navLabel }}">İçerik</p>
    <a href="{{ route('admin.blogList') }}" title="Blog" class="{{ $navLink }} {{ request()->routeIs('admin.blog*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></svg>
      <span class="admin-nav-text">Blog</span>
    </a>
    <a href="{{ route('admin.newsletterList') }}" title="Bülten" class="{{ $navLink }} {{ request()->routeIs('admin.newsletter*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v12H4zM4 8l8 5 8-5"/></svg>
      <span class="admin-nav-text">Bülten</span>
    </a>
    <a href="{{ route('admin.companyList') }}" title="Referans Firmalar" class="{{ $navLink }} {{ request()->routeIs('admin.company*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/></svg>
      <span class="admin-nav-text">Referans Firmalar</span>
    </a>

    <p class="{{ $navLabel }}">Sistem</p>
    <a href="{{ route('admin.account') }}" title="Hesabım" class="{{ $navLink }} {{ request()->routeIs('admin.account*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>
      <span class="admin-nav-text">Hesabım</span>
    </a>
    <a href="{{ route('admin.settings') }}" title="Ayarlar" class="{{ $navLink }} {{ request()->routeIs('admin.settings*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.6.77 1.05 1.4 1.2H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      <span class="admin-nav-text">Ayarlar</span>
    </a>
    <a href="{{ route('admin.userList') }}" title="Kullanıcılar" class="{{ $navLink }} {{ request()->routeIs('admin.user*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <span class="admin-nav-text">Kullanıcılar</span>
    </a>
    <a href="{{ url('/horizon') }}" target="_blank" rel="noopener noreferrer" title="Horizon"
       class="{{ $navLink }} {{ request()->is('horizon*') ? $navLinkActive : '' }}">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      <span class="admin-nav-text">Horizon</span>
    </a>
  </nav>

  {{-- Siteye dön & çıkış --}}
  <div class="admin-sidebar-footer shrink-0 border-t-[3px] border-ink p-2 space-y-2">
    <a href="{{ route('index') }}" target="_blank" title="Siteyi Görüntüle"
       class="admin-footer-btn flex items-center justify-center gap-2 border-2 border-on-dark/25 px-3 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-white/10">
      <svg width="16" height="16" class="shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
      <span class="admin-nav-text">Siteyi Görüntüle</span>
    </a>
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" title="Çıkış Yap"
              class="admin-footer-btn flex w-full items-center justify-center gap-2 border-2 border-on-dark/25 px-3 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-white/10">
        <svg width="16" height="16" class="shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        <span class="admin-nav-text">Çıkış Yap</span>
      </button>
    </form>
  </div>
</aside>
