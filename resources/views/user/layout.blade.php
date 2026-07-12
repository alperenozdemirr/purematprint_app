<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.avif" type="image/avif">
  <link rel="icon" href="favicon-32.png" type="image/png" sizes="32x32">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <title> @yield('title') PureMatPrint — Baskı & Tabela Stüdyosu</title>
  <meta name="description" content="PureMatPrint — Tabela, baskı ve kurumsal kimlik. Cesur tasarım, kusursuz üretim.">
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
            announce: '#b61d0f',
            'header-hover': 'rgba(250, 246, 238, 0.14)',
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
          },
          fontFamily: {
            heading: ['Petrona', 'Georgia', 'Times New Roman', 'serif'],
            body: ['IBM Plex Sans', 'system-ui', '-apple-system', 'sans-serif'],
          },
          spacing: { announce: '44px' },
          maxWidth: { site: '1280px', catalog: '1544px' },
          boxShadow: {
            brutal: '6px 6px 0 rgba(26, 26, 26, 0.68)',
            'brutal-sm': '4px 4px 0 rgba(26, 26, 26, 0.68)',
            ui: '4px 4px 0 rgba(90, 84, 78, 0.2)',
            'ui-sm': '3px 3px 0 rgba(90, 84, 78, 0.16)',
          },
          fontSize: {
            'hero-title': ['clamp(2.75rem, 7vw, 5rem)', { lineHeight: '1.15' }],
            'pdp-title': ['clamp(2rem, 3vw, 2.75rem)', { lineHeight: '1.15' }],
            'page-title': ['clamp(1.75rem, 2.5vw, 2rem)', { lineHeight: '1.15' }],
            'section-title': ['clamp(1.375rem, 2vw, 1.625rem)', { lineHeight: '1.15' }],
            'feature-title': ['clamp(1.125rem, 1.6vw, 1.25rem)', { lineHeight: '1.15' }],
            'card-title': ['1rem', { lineHeight: '1.35' }],
          },
          keyframes: {
            'i5-ticker': {
              from: { transform: 'translate3d(0, 0, 0)' },
              to: { transform: 'translate3d(var(--ticker-shift, -50%), 0, 0)' },
            },
            'i5-pdp-lightbox-in': {
              from: { opacity: '0', transform: 'scale(0.96) translateY(8px)' },
              to: { opacity: '1', transform: 'scale(1) translateY(0)' },
            },
            'pmp-loader-logo': {
              '0%, 100%': { transform: 'translate(0, 0)', boxShadow: '6px 6px 0 rgba(26, 26, 26, 0.68)' },
              '50%': { transform: 'translate(-3px, -3px)', boxShadow: '9px 9px 0 rgba(26, 26, 26, 0.68)' },
            },
            'pmp-loader-progress': {
              '0%': { width: '0' },
              '45%': { width: '78%' },
              '100%': { width: '100%' },
            },
          },
          animation: {
            'i5-ticker': 'i5-ticker var(--ticker-duration, 28s) linear infinite',
            'i5-pdp-lightbox-in': 'i5-pdp-lightbox-in 0.28s ease',
            'pmp-loader-logo': 'pmp-loader-logo 1.1s steps(2, end) infinite',
            'pmp-loader-progress': 'pmp-loader-progress 1.6s ease-in-out infinite',
          },
        },
      },
    };
  </script>
  <style>
    html { scroll-behavior: smooth; box-sizing: border-box; }
    *, *::before, *::after { box-sizing: inherit; }
    img { max-width: 100%; height: auto; display: block; }
    [data-i5="product__media"] { width: 100%; }
    [data-i5="product__media"] img,
    [data-i5="pdp-gallery__slide"] img,
    [data-i5="pdp-gallery__thumb"] img {
      width: 100%;
      height: 100%;
      max-width: none;
      object-fit: cover;
    }
    html.pmp-is-loading, html.pmp-lightbox-open { overflow: hidden; }
    [data-i5="pdp-lightbox"][hidden] { display: none !important; }
    [data-i5="cart-item__img"] img {
      width: 100%;
      height: 100%;
      max-width: none;
      object-fit: cover;
    }
    details summary::-webkit-details-marker { display: none; }
    #i5-mobile-menu nav { -webkit-overflow-scrolling: touch; }
    #i5-mobile-menu details > summary::-webkit-details-marker { display: none; }
    #i5-mobile-menu details > summary::after {
      content: '+';
      font-family: 'IBM Plex Sans', sans-serif;
      font-size: 22px;
      font-weight: 700;
      line-height: 1;
      flex-shrink: 0;
    }
    #i5-mobile-menu details[open] > summary::after { content: '−'; }
    [data-i5="band__track-wrap"] {
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    [data-i5="band__track-wrap"]::-webkit-scrollbar {
      display: none;
    }
  </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=Petrona:wght@500;600;700&display=swap">
  <script src="{{asset('user')}}/js/tw-classes.js"></script>
  <script src="{{asset('user')}}/js/loader.js"></script>
  </head>
<body class="font-body text-base leading-[1.55] text-ink bg-bg antialiased pt-announce overflow-x-hidden selection:bg-action-muted selection:text-on-dark" data-pmp-site>
  <!-- Site chrome — index5.js: duyuru, header, mobil menü, arama -->
  <div class="pmp-announce fixed inset-x-0 top-0 z-[300] flex min-h-[44px] items-center justify-center border-b-[3px] border-ink bg-announce px-5 text-center font-body text-[13px] font-semibold uppercase tracking-wide text-on-dark">
    500₺ üzeri ücretsiz kargo — <a class="underline underline-offset-[3px]" href="products.html">Alışverişe Başla</a>
  </div>

  <header data-i5="header" class="sticky top-announce z-[200] overflow-visible border-b-[3px] border-ink bg-announce text-on-dark min-[1040px]:grid min-[1040px]:grid-cols-[auto_1fr_auto] min-[1040px]:grid-rows-[64px] min-[1040px]:items-stretch">
    <div class="w-full max-w-full p-0 min-[1040px]:contents">
      <div class="flex min-h-16 items-stretch min-[1040px]:contents">
        <button type="button" class="flex w-16 shrink-0 items-center justify-center border-r-[3px] border-ink bg-transparent text-on-dark transition-colors hover:bg-header-hover min-[1040px]:hidden" id="i5-burger" aria-label="Menü">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        <a href="{{ route('index') }}" class="pmp-header-logo flex flex-1 items-center justify-center border-r-[3px] border-ink px-5 min-[1040px]:col-start-1 min-[1040px]:row-start-1 min-[1040px]:min-h-16 min-[1040px]:flex-none min-[1040px]:justify-start min-[1040px]:px-7">
          <img src="{{asset('shared_directory')}}/logo.avif" alt="PureMatPrint" class="h-7 w-auto brightness-0 invert">
        </a>
        <div class="ml-auto flex items-stretch min-[1040px]:col-start-3 min-[1040px]:row-start-1 min-[1040px]:ml-0">
          <button type="button" class="flex h-full w-16 items-center justify-center bg-transparent text-on-dark transition-colors hover:bg-header-hover max-[1039px]:hidden" id="i5-search-open" aria-label="Ara">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.824" cy="10.824" r="7.824"/><path stroke-linecap="square" d="m16.971 16.971 4.47 4.47"/></svg>
          </button>
          @if ($isUserLoggedIn)
          <div class="relative max-[1039px]:hidden group/account before:content-[''] before:absolute before:inset-x-0 before:top-full before:h-2" data-i5="account-dropdown">
            <button type="button" class="flex items-center justify-center w-16 h-full text-on-dark transition-colors hover:bg-header-hover group-hover/account:bg-header-hover group-[.is-open]/account:bg-header-hover" data-i5="account-trigger" aria-label="Hesabım" aria-haspopup="true" aria-expanded="false">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </button>
            <div class="absolute right-0 top-[calc(100%+3px)] z-[90] w-[min(100vw-2.5rem,260px)] border-[3px] border-ink bg-surface shadow-brutal opacity-0 invisible translate-y-1 transition-all duration-200 pointer-events-none group-hover/account:opacity-100 group-hover/account:visible group-hover/account:translate-y-0 group-hover/account:pointer-events-auto group-focus-within/account:opacity-100 group-focus-within/account:visible group-focus-within/account:translate-y-0 group-focus-within/account:pointer-events-auto group-[.is-open]/account:opacity-100 group-[.is-open]/account:visible group-[.is-open]/account:translate-y-0 group-[.is-open]/account:pointer-events-auto" data-i5="account-panel">
              <div class="flex items-center gap-3 px-4 py-3.5 border-b-[3px] border-ink bg-bg">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center border-[3px] border-ink bg-accent font-body text-[11px] font-bold uppercase tracking-[0.04em] text-on-dark" aria-hidden="true">{{ $userInitials }}</span>
                <div class="min-w-0">
                  <p class="truncate font-body text-[13px] font-bold uppercase tracking-[0.02em] text-ink">{{ $authUser->name }}</p>
                  <p class="truncate text-[11px] text-muted">{{ $authUser->email }}</p>
                </div>
              </div>
              <nav class="grid p-2" aria-label="Hesap menüsü">
                <a href="{{ route('account') }}" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70">
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                  Hesabım
                </a>
                <a href="{{ route('orderList') }}" class="flex items-center gap-2.5 px-3.5 py-3 font-body text-xs font-bold uppercase tracking-[0.04em] text-muted transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0 [&_svg]:opacity-70">
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                  Siparişlerim
                </a>
                <form action="{{ route('logout') }}" method="post" class="mt-1 border-t border-ink/10 pt-1">
                  @csrf
                  <button type="submit" class="flex w-full items-center gap-2.5 px-3.5 py-3 text-left font-body text-xs font-bold uppercase tracking-[0.04em] text-announce transition-[background,color] hover:bg-hover hover:text-ink [&_svg]:shrink-0">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Çıkış Yap
                  </button>
                </form>
              </nav>
            </div>
          </div>
          @else
          <a href="{{ route('loginPage') }}" class="flex items-center justify-center w-16 h-full text-on-dark transition-colors hover:bg-header-hover max-[1039px]:hidden" aria-label="Giriş Yap">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
          </a>
          @endif
          <a href="{{ route('cart') }}" class="flex items-center justify-center w-16 relative text-on-dark transition-colors hover:bg-header-hover" aria-label="{{ $cartCount > 0 ? 'Sepet ('.$cartCount.' ürün)' : 'Sepet' }}" data-i5="header__cart">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
            @if ($cartCount > 0)
            <span class="absolute top-[10px] right-[10px] min-w-[18px] h-[18px] px-1 bg-on-dark text-announce font-body text-[10px] font-bold leading-none border-2 border-ink flex items-center justify-center pointer-events-none">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
            @endif
          </a>
          <a href="products.html" class="hidden items-center border-l-[3px] border-ink bg-on-dark px-6 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-announce transition-colors hover:bg-cream min-[768px]:flex">Keşfet</a>
        </div>
      </div>
    </div>

    <nav class="relative hidden border-t-[3px] border-ink bg-announce min-[1040px]:col-start-2 min-[1040px]:row-start-1 min-[1040px]:flex min-[1040px]:min-h-16 min-[1040px]:min-w-0 min-[1040px]:items-center min-[1040px]:justify-center min-[1040px]:border-t-0 min-[1040px]:static" aria-label="Kategoriler">
      <div class="mx-auto max-w-site px-8 min-[1040px]:flex min-[1040px]:w-full min-[1040px]:max-w-none min-[1040px]:justify-center min-[1040px]:px-4">
        <ul class="m-0 flex min-h-12 list-none items-center justify-center gap-7 p-0 min-[1040px]:h-16 min-[1040px]:min-h-0 min-[1040px]:items-stretch min-[1200px]:gap-7">
          <li><a href="{{ route('shops') }}" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Tüm Ürünler</a></li>
          <li class="static min-[1040px]:flex min-[1040px]:items-center group/mega before:content-[''] before:absolute before:inset-x-0 before:bottom-full before:h-3" data-i5="mega-item">
            <a href="category.html?cat=tabela" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Tabela & Afiş</a>
            <div class="absolute inset-x-0 top-[calc(100%+3px)] w-full bg-surface border-b-[3px] border-ink opacity-0 invisible translate-y-1 transition-all duration-200 pointer-events-none z-[80] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:pointer-events-auto min-[1040px]:translate-y-0 group-[.is-open]/mega:opacity-100 group-[.is-open]/mega:visible group-[.is-open]/mega:translate-y-0 group-[.is-open]/mega:pointer-events-auto" data-i5="mega-panel">
              <div class="mx-auto max-w-[1440px] px-12 py-9 pb-11">
                <div class="pmp-mega-grid grid grid-cols-2 gap-x-12 gap-y-1 min-[1200px]:grid-cols-4 min-[1200px]:gap-x-14 min-[1200px]:gap-y-1.5">
                  <a class="block py-2 col-span-full mb-2 pb-3.5 border-b border-ink/10 font-body text-xs font-bold uppercase tracking-wider text-ink hover:text-accent" href="category.html?cat=tabela">Tümünü Gör</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=acik-hava-tabela">Açık Hava Tabelası</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=a-frame">A-Frame Tabela</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=led-lightbox">LED Lightbox</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=roll-up-banner">Roll-Up Banner</a>
                </div>
              </div>
            </div>
          </li>
          <li class="static min-[1040px]:flex min-[1040px]:items-center group/mega before:content-[''] before:absolute before:inset-x-0 before:bottom-full before:h-3" data-i5="mega-item">
            <a href="category.html?cat=kartvizit" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Menü & Display</a>
            <div class="absolute inset-x-0 top-[calc(100%+3px)] w-full bg-surface border-b-[3px] border-ink opacity-0 invisible translate-y-1 transition-all duration-200 pointer-events-none z-[80] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:pointer-events-auto min-[1040px]:translate-y-0 group-[.is-open]/mega:opacity-100 group-[.is-open]/mega:visible group-[.is-open]/mega:translate-y-0 group-[.is-open]/mega:pointer-events-auto" data-i5="mega-panel">
              <div class="mx-auto max-w-[1440px] px-12 py-9 pb-11">
                <div class="pmp-mega-grid grid grid-cols-2 gap-x-12 gap-y-1 min-[1200px]:grid-cols-4 min-[1200px]:gap-x-14 min-[1200px]:gap-y-1.5">
                  <a class="block py-2 col-span-full mb-2 pb-3.5 border-b border-ink/10 font-body text-xs font-bold uppercase tracking-wider text-ink hover:text-accent" href="category.html?cat=kartvizit">Tümünü Gör</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=magnet-afis">Magnet Afiş Seti</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="product.html?id=roll-up-banner">Roll-Up Banner</a>
                </div>
              </div>
            </div>
          </li>
          <li class="static min-[1040px]:flex min-[1040px]:items-center group/mega before:content-[''] before:absolute before:inset-x-0 before:bottom-full before:h-3" data-i5="mega-item">
            <a href="category.html?cat=kurumsal" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Baskı & Marka</a>
            <div class="absolute inset-x-0 top-[calc(100%+3px)] w-full bg-surface border-b-[3px] border-ink opacity-0 invisible translate-y-1 transition-all duration-200 pointer-events-none z-[80] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:pointer-events-auto min-[1040px]:translate-y-0 group-[.is-open]/mega:opacity-100 group-[.is-open]/mega:visible group-[.is-open]/mega:translate-y-0 group-[.is-open]/mega:pointer-events-auto" data-i5="mega-panel">
              <div class="mx-auto max-w-[1440px] px-12 py-9 pb-11">
                <div class="pmp-mega-grid grid grid-cols-2 gap-x-12 gap-y-1 min-[1200px]:grid-cols-4 min-[1200px]:gap-x-14 min-[1200px]:gap-y-1.5">
                  <a class="block py-2 col-span-full mb-2 pb-3.5 border-b border-ink/10 font-body text-xs font-bold uppercase tracking-wider text-ink hover:text-accent" href="products.html">Tümünü Gör</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="category.html?cat=kartvizit">Kartvizit</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="category.html?cat=kurumsal">Kurumsal Kimlik</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="category.html?cat=ambalaj">Ambalaj</a>
                  <a class="block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent" href="category.html?cat=dijital">Dijital Baskı</a>
                </div>
              </div>
            </div>
          </li>
          <li><a href="{{ route('collectionList') }}" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Koleksiyonlar</a></li>
          <li><a href="bestsellers.html" class="relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100"  data-i5="mega-nav__link">Çok Satanlar</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="fixed inset-0 z-[400] bg-ink/60 opacity-0 invisible transition-[opacity,visibility] duration-300 [&.open]:opacity-100 [&.open]:visible" id="i5-mobile-overlay" data-i5="mobile-overlay"></div>
  <aside class="fixed top-0 left-0 z-[500] h-dvh w-[min(100%,400px)] -translate-x-full flex flex-col border-r-[3px] border-ink bg-surface transition-transform duration-300 [&.open]:translate-x-0" id="i5-mobile-menu" aria-hidden="true" data-i5="mobile">
    <div class="flex items-center justify-between p-5 border-b-[3px] border-ink shrink-0">
      <a href="{{ route('index') }}" class="flex items-center"><img src="{{asset('shared_directory')}}/logo.avif" alt="PureMatPrint" class="h-[26px] w-auto"></a>
      <button type="button" class="flex items-center justify-center w-11 h-11 bg-surface border-[3px] border-ink shadow-brutal-sm text-ink shrink-0 hover:bg-hover" id="i5-mobile-close" aria-label="Kapat">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
    <div class="[padding:16px_20px] border-b-[3px] border-ink shrink-0">
      <form class="flex items-stretch border-[3px] border-ink shadow-brutal-sm bg-surface [&_input]:flex-1 [&_input]:min-w-0 [&_input]:border-0 [&_input]:bg-transparent [&_input]:px-4 [&_input]:py-3.5 [&_input]:font-body [&_input]:text-[15px] [&_input]:text-ink [&_input]:outline-none [&_button]:flex [&_button]:shrink-0 [&_button]:w-[52px] [&_button]:items-center [&_button]:justify-center [&_button]:border-0 [&_button]:border-l-[3px] [&_button]:border-l-ink [&_button]:bg-action [&_button]:text-on-dark [&_button]:cursor-pointer" action="products.html" method="get">
        <input type="search" name="q" placeholder="Ürün ara..." aria-label="Ara">
        <button type="submit" aria-label="Ara"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.824" cy="10.824" r="7.824"/><path stroke-linecap="square" d="m16.971 16.971 4.47 4.47"/></svg>
        </button>
      </form>
      <div class="grid [grid-template-columns:1fr_1fr] mt-5 border-[3px] border-ink shadow-brutal-sm [&>a:first-child]:border-r-[3px] [&>a:first-child]:border-r-ink">
        <a href="{{ route('cart') }}" class="flex items-center justify-center gap-2 [padding:14px_10px] bg-surface font-body text-xs font-bold tracking-[0.06em] uppercase text-ink hover:bg-hover [&.is-active]:bg-action [&.is-active]:text-on-dark">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
          <span>Sepet</span>
        </a>
        @if ($isUserLoggedIn)
        <a href="{{ route('account') }}" class="flex items-center justify-center gap-2 [padding:14px_10px] bg-surface font-body text-xs font-bold tracking-[0.06em] uppercase text-ink hover:bg-hover [&.is-active]:bg-action [&.is-active]:text-on-dark">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
          <span>Hesabım</span>
        </a>
        @else
        <a href="{{ route('loginPage') }}" class="flex items-center justify-center gap-2 [padding:14px_10px] bg-surface font-body text-xs font-bold tracking-[0.06em] uppercase text-ink hover:bg-hover [&.is-active]:bg-action [&.is-active]:text-on-dark">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
          <span>Giriş</span>
        </a>
        @endif
      </div>
    </div>
    <nav class="flex-1 p-0 [overflow-y:auto] [-webkit-overflow-scrolling:touch]">
      <p class="font-body text-xs font-bold tracking-[0.12em] uppercase text-accent [padding:16px_20px_8px] m-0 border-t-[3px] border-ink bg-bg">Katalog</p>
      <a href="{{ route('shops') }}" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Tüm Ürünler</a>
      <details class="[&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-3 [&_summary]:font-body [&_summary]:text-[20px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:[padding:16px_20px] [&_summary]:border-b-2 [&_summary]:border-ink [&_summary]:cursor-pointer [&_summary]:list-none [&_summary]:bg-surface hover:[&_summary]:bg-hover [&_a]:block [&_a]:font-body [&_a]:text-[15px] [&_a]:font-medium [&_a]:normal-case [&_a]:[padding:12px_20px_12px_32px] [&_a]:border-b [&_a]:border-ink [&_a]:bg-bg hover:[&_a]:bg-hover bg-surface">
        <summary>Tabela &amp; Afiş</summary>
        <a href="category.html?cat=tabela">Tümünü Gör</a>
        <a href="product.html?id=acik-hava-tabela">Açık Hava Tabelası</a>
        <a href="product.html?id=a-frame">A-Frame Tabela</a>
        <a href="product.html?id=led-lightbox">LED Lightbox</a>
        <a href="product.html?id=roll-up-banner">Roll-Up Banner</a>
      </details>
      <details class="[&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-3 [&_summary]:font-body [&_summary]:text-[20px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:[padding:16px_20px] [&_summary]:border-b-2 [&_summary]:border-ink [&_summary]:cursor-pointer [&_summary]:list-none [&_summary]:bg-surface hover:[&_summary]:bg-hover [&_a]:block [&_a]:font-body [&_a]:text-[15px] [&_a]:font-medium [&_a]:normal-case [&_a]:[padding:12px_20px_12px_32px] [&_a]:border-b [&_a]:border-ink [&_a]:bg-bg hover:[&_a]:bg-hover bg-surface">
        <summary>Menü &amp; Display</summary>
        <a href="category.html?cat=kartvizit">Tümünü Gör</a>
        <a href="product.html?id=magnet-afis">Magnet Afiş Seti</a>
        <a href="product.html?id=roll-up-banner">Roll-Up Banner</a>
      </details>
      <details class="[&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-3 [&_summary]:font-body [&_summary]:text-[20px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:[padding:16px_20px] [&_summary]:border-b-2 [&_summary]:border-ink [&_summary]:cursor-pointer [&_summary]:list-none [&_summary]:bg-surface hover:[&_summary]:bg-hover [&_a]:block [&_a]:font-body [&_a]:text-[15px] [&_a]:font-medium [&_a]:normal-case [&_a]:[padding:12px_20px_12px_32px] [&_a]:border-b [&_a]:border-ink [&_a]:bg-bg hover:[&_a]:bg-hover bg-surface">
        <summary>Baskı &amp; Marka</summary>
        <a href="category.html?cat=kartvizit">Kartvizit</a>
        <a href="category.html?cat=kurumsal">Kurumsal Kimlik</a>
        <a href="category.html?cat=ambalaj">Ambalaj</a>
        <a href="category.html?cat=dijital">Dijital Baskı</a>
      </details>
      <a href="{{ route('collectionList') }}" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Koleksiyonlar</a>
      <p class="font-body text-xs font-bold tracking-[0.12em] uppercase text-accent [padding:16px_20px_8px] m-0 border-t-[3px] border-ink bg-bg">Keşfet</p>
      <a href="{{ route('collectionList') }}" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Koleksiyonlar</a>
      <a href="bestsellers.html" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Çok Satanlar</a>
      <a href="#surec" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Süreç</a>
      <a href="#yorumlar" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Referanslar</a>
      <p class="font-body text-xs font-bold tracking-[0.12em] uppercase text-accent [padding:16px_20px_8px] m-0 border-t-[3px] border-ink bg-bg">Hesap</p>
      <details class="[&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-3 [&_summary]:font-body [&_summary]:text-[20px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:[padding:16px_20px] [&_summary]:border-b-2 [&_summary]:border-ink [&_summary]:cursor-pointer [&_summary]:list-none [&_summary]:bg-surface hover:[&_summary]:bg-hover [&_a]:block [&_a]:font-body [&_a]:text-[15px] [&_a]:font-medium [&_a]:normal-case [&_a]:[padding:12px_20px_12px_32px] [&_a]:border-b [&_a]:border-ink [&_a]:bg-bg hover:[&_a]:bg-hover bg-surface">
        <summary>Hesabım</summary>
        @if ($isUserLoggedIn)
        <a href="{{ route('account') }}">Profil Bilgilerim</a>
        <a href="{{ route('orderList') }}">Siparişlerim</a>
        <a href="addresses.html">Adreslerim</a>
        <a href="{{ route('cart') }}">Sepetim</a>
        <form action="{{ route('logout') }}" method="post">
          @csrf
          <button type="submit" class="block w-full text-left font-body text-[15px] font-medium normal-case [padding:12px_20px_12px_32px] border-b border-ink bg-bg hover:bg-hover">Çıkış Yap</button>
        </form>
        @else
        <a href="{{ route('loginPage') }}">Giriş Yap</a>
        <a href="{{ route('registerPage') }}">Kayıt Ol</a>
        @endif
      </details>
      <p class="font-body text-xs font-bold tracking-[0.12em] uppercase text-accent [padding:16px_20px_8px] m-0 border-t-[3px] border-ink bg-bg">Kurumsal</p>
      <a href="about.html" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">Hakkımızda</a>
      <a href="contact.html" class="block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent" data-i5="mobile__link">İletişim</a>
    </nav>
    <div class="p-5 border-t-[3px] border-ink shrink-0">
      <a href="products.html" class="block w-full m-0 p-4 text-center bg-action text-on-dark font-body text-[13px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 shadow-ui-sm hover:bg-action-hover">Alışverişe Başla</a>
    </div>
  </aside>

  <div class="fixed inset-0 z-[500] flex items-center justify-center bg-[rgba(255,252,247,0.98)] opacity-0 invisible transition-[opacity,visibility] duration-200 [&.open]:opacity-100 [&.open]:visible" id="i5-search" aria-hidden="true" data-i5="search">
    <button type="button" class="absolute right-5 flex h-12 w-12 items-center justify-center border-[3px] border-ink bg-surface text-ink shadow-brutal-sm transition-colors hover:bg-hover top-[calc(44px+12px)] z-10" id="i5-search-close" aria-label="Kapat">
      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
    <form class="flex w-[min(720px,90vw)] items-center gap-4 border-[3px] border-ink bg-surface px-6 py-5 shadow-brutal" action="products.html" method="get">
      <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.824" cy="10.824" r="7.824"/><path stroke-linecap="square" d="m16.971 16.971 4.47 4.47"/></svg>
      <input type="search" name="q" placeholder="Ürün, kategori veya marka ara..." aria-label="Ara" class="min-w-0 flex-1 border-0 bg-transparent font-body text-[clamp(1.25rem,4vw,2rem)] font-bold uppercase tracking-tight text-ink outline-none placeholder:text-muted placeholder:opacity-80">
    </form>
  </div>


@yield('content')


   <!-- Footer -->
  <!-- Site footer -->
  <footer class="bg-dark text-on-dark pt-14 pb-8 border-t-[3px] border-ink" data-i5="footer">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div class="grid gap-10 pb-10 border-b border-on-dark/[0.14] min-[768px]:grid-cols-[minmax(0,1.25fr)_minmax(0,0.75fr)] min-[768px]:gap-16 min-[768px]:items-start" data-i5="footer__upper">
        <div >
          <h2 class="font-heading text-[clamp(1.2rem,2.4vw,1.45rem)] font-bold uppercase tracking-[0.05em] mb-3" data-i5="footer__heading">Bültene katıl</h2>
          <p class="text-[15px] leading-[1.65] opacity-[0.72] mb-6 max-w-[38ch]" data-i5="footer__lead">Yeni ürünler, proje hikayeleri ve özel indirimler için abone olun.</p>
          <form class="flex flex-col gap-2.5 max-w-[440px] min-[480px]:flex-row [&_input]:min-w-0 [&_input]:flex-1 [&_input]:px-4 [&_input]:py-3.5 [&_input]:border-2 [&_input]:border-on-dark/[0.28] [&_input]:bg-transparent [&_input]:text-inherit [&_input]:text-[15px] [&_input]:outline-none [&_input]:placeholder:text-on-dark/[0.45] focus:[&_input]:border-on-dark/60 [&_button]:px-6 [&_button]:py-3.5 [&_button]:bg-on-dark [&_button]:text-ink [&_button]:font-body [&_button]:text-[13px] [&_button]:font-bold [&_button]:uppercase [&_button]:tracking-[0.04em] [&_button]:whitespace-nowrap [&_button]:transition-colors hover:[&_button]:bg-hover" data-newsletter-form data-i5="footer__form">
            <input type="email" placeholder="E-posta adresiniz" required aria-label="E-posta" >
            <button type="submit" >Abone Ol</button>
          </form>
        </div>
        <nav class="[&_ul]:flex [&_ul]:flex-col [&_ul]:gap-[11px] [&_a]:text-sm [&_a]:text-on-dark/[0.72] [&_a]:transition-colors hover:[&_a]:text-on-dark" aria-label="Footer" data-i5="footer__nav">
          <ul>
            <li><a href="{{ route('shops') }}">Tüm Ürünler</a></li>
            <li><a href="{{ route('collectionList') }}">Koleksiyonlar</a></li>
            <li><a href="bestsellers.html">Çok Satanlar</a></li>
            <li><a href="about.html">Hakkımızda</a></li>
            <li><a href="contact.html">İletişim</a></li>
            <li><a href="#">Kargo &amp; Teslimat</a></li>
            @if ($isUserLoggedIn)
            <li><a href="{{ route('account') }}">Profil Bilgilerim</a></li>
            <li><a href="{{ route('orderList') }}">Siparişlerim</a></li>
            @else
            <li><a href="{{ route('loginPage') }}">Giriş Yap</a></li>
            <li><a href="{{ route('registerPage') }}">Kayıt Ol</a></li>
            @endif
            <li><a href="blog.html">Blog</a></li>
          </ul>
        </nav>
      </div>
      <div class="flex flex-col gap-6 pt-7 min-[768px]:flex-row min-[768px]:items-center min-[768px]:justify-between min-[768px]:gap-8" data-i5="footer__lower">
        <div class="flex flex-wrap gap-2 [&_span]:inline-flex [&_span]:items-center [&_span]:justify-center [&_span]:min-w-[38px] [&_span]:h-6 [&_span]:px-2 [&_span]:bg-on-dark [&_span]:text-ink [&_span]:font-body [&_span]:text-[9px] [&_span]:font-bold [&_span]:tracking-[0.05em] [&_span]:uppercase" aria-label="Ödeme yöntemleri" data-i5="footer__payments">
          <span>Visa</span>
          <span>Mastercard</span>
          <span>Troy</span>
          <span>Amex</span>
        </div>
        <div class="flex flex-col gap-2.5 min-[768px]:flex-row min-[768px]:flex-wrap min-[768px]:items-center min-[768px]:justify-end min-[768px]:gap-x-5 min-[768px]:gap-y-2.5" data-i5="footer__meta">
          <p class="m-0 text-xs opacity-[0.55]" data-i5="footer__copy">&copy; 2026 PureMatPrint</p>
          <nav class="flex flex-wrap items-center gap-x-4 gap-y-2 [&_a]:text-[11px] [&_a]:text-on-dark/50 [&_a]:transition-colors hover:[&_a]:text-on-dark" aria-label="Yasal" data-i5="footer__legal">
            <a href="privacy.html">KVKK / Gizlilik</a>
            <a href="cookies.html">Çerez Politikası</a>
            <a href="distance-sales.html">Mesafeli Satış Sözleşmesi</a>
          </nav>
        </div>
      </div>
    </div>
  </footer>
  @stack('scripts')
  <!-- index5.js (menü, carousel, ticker) -->
  <script src="{{asset('user')}}/js/index5.js"></script>
</body>
</html>
