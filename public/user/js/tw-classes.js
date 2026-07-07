/**
 * tw-classes.js — JS ile oluşturulan elemanlar için Tailwind sınıf sözlüğü
 *
 * NE İÇİN:
 *   HTML'de class yokken JS'in DOM'a eklediği parçalar (loader, pagination, lightbox).
 *
 * NE DEĞİL:
 *   HTML sayfalarındaki class'ların kaynağı değil — onlar doğrudan HTML'de yazar.
 *
 * matchesI5('ad') — elemanın data-i5 veya data-i5-tags ile eşleşip eşleşmediğini kontrol eder
 */
window.matchesI5 = (n, el = document.body) =>
  el?.matches?.(`[data-i5="${n}"], [data-i5-tags~="${n}"]`) ?? false;

/** Dinamik eleman stilleri — key: bileşen__parça, value: Tailwind class string */
window.TW = {
  // --- Açılış loader (loader.js) ---
  loader:
    'fixed inset-0 z-[100000] grid grid-rows-[44px_1fr] bg-[rgba(250,246,238,0.98)] backdrop-blur-[12px] text-ink transition-[opacity,transform] duration-500 [&.is-done]:opacity-0 [&.is-done]:-translate-y-3 [&.is-done]:pointer-events-none motion-reduce:transition-none',
  loader__announce:
    'flex items-center justify-center bg-announce text-on-dark border-b-[3px] border-ink font-body text-[11px] font-semibold tracking-[0.14em] uppercase',
  loader__main: 'flex flex-col items-center justify-center gap-7 py-8 px-6',
  loader__logo_box:
    'flex items-center justify-center w-[min(220px,72vw)] py-[22px] px-7 bg-surface border-[3px] border-ink shadow-brutal animate-pmp-loader-logo motion-reduce:animate-none',
  loader__copy: 'flex flex-col items-center gap-[14px] w-[min(320px,86vw)]',
  loader__label:
    'font-body text-xs font-semibold tracking-[0.12em] uppercase text-ink min-h-[1.2em] py-2.5 px-4 bg-surface/95 border-2 border-ink/90 shadow-brutal-sm transition-[opacity,transform] duration-200 ease-out motion-reduce:transition-none [&.is-changing]:opacity-55 [&.is-changing]:translate-y-px',
  loader__track: 'w-full h-[14px] border-[3px] border-ink bg-surface shadow-brutal-sm overflow-hidden',
  loader__bar:
    'h-full w-0 bg-gradient-to-r from-announce to-accent animate-pmp-loader-progress motion-reduce:animate-none [.pmp-loader.is-done_&]:w-full [.pmp-loader.is-done_&]:animate-none',
  loader__stamps: 'flex items-center gap-2.5',
  loader__stamp:
    'w-4 h-4 border-2 border-ink bg-cream shadow-[3px_3px_0_#1a1a1a] animate-pmp-loader-stamp motion-reduce:animate-none',
  loader__stamp_delay2: '[animation-delay:150ms]',
  loader__stamp_delay3: '[animation-delay:300ms]',
  // --- Ürün grid sayfalama (index5.js) ---
  pagination__btn:
    'flex items-center justify-center min-w-11 h-11 px-3.5 border-[3px] border-ink shadow-brutal-sm bg-surface font-body text-sm font-bold text-ink transition-all hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal hover:bg-hover [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:cursor-default disabled:opacity-35 disabled:cursor-not-allowed',
  pagination__dots: 'font-body text-sm font-bold text-muted px-1 select-none',
  // --- Sabit WhatsApp / yukarı çık (index5.js) ---
  float_stack:
    'fixed bottom-6 right-6 z-[350] flex flex-col gap-3 items-center max-[767px]:bottom-5 max-[767px]:right-4 max-[767px]:gap-2.5',
  float_btn:
    'w-12 h-12 flex items-center justify-center border-[3px] border-ink shadow-brutal-sm transition-[transform,box-shadow,background,opacity,visibility] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal max-[767px]:w-11 max-[767px]:h-11',
  whatsapp: 'bg-[#25d366] text-white hover:bg-[#1ebe57]',
  back_top:
    'bg-action text-on-dark opacity-0 invisible translate-y-3 pointer-events-none [&.is-visible]:opacity-100 [&.is-visible]:visible [&.is-visible]:translate-y-0 [&.is-visible]:pointer-events-auto hover:bg-action-hover',
  // --- Header sepet rozeti (cart-badge.js) ---
  header__cart_count:
    'absolute top-[10px] right-[10px] min-w-[18px] h-[18px] px-1 bg-on-dark text-announce font-body text-[10px] font-bold leading-none border-2 border-ink flex items-center justify-center pointer-events-none [hidden]:!hidden',
  // --- Ürün detay lightbox (product.js) ---
  pdp_lightbox:
    'fixed inset-0 z-[100001] flex items-center justify-center p-5 max-[640px]:p-3 [hidden]:!hidden',
  pdp_lightbox__backdrop: 'absolute inset-0 p-0 border-0 bg-ink/80 backdrop-blur-[10px] cursor-pointer',
  pdp_lightbox__panel:
    'relative z-[1] w-[min(960px,96vw)] max-h-[92vh] border-[3px] border-ink shadow-brutal bg-surface animate-i5-pdp-lightbox-in',
  pdp_lightbox__close:
    'absolute -top-[18px] -right-[18px] z-[2] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-surface cursor-pointer transition-[transform,background] hover:bg-hover hover:-translate-x-px hover:-translate-y-px max-[640px]:top-2 max-[640px]:right-2',
  pdp_lightbox__nav:
    'absolute top-1/2 z-[2] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-surface/95 -translate-y-1/2 cursor-pointer transition-[transform,background] hover:bg-surface hover:-translate-x-px hover:-translate-y-1/2 disabled:opacity-35 disabled:cursor-not-allowed',
  pdp_lightbox__nav__prev: 'left-[-22px] max-[640px]:left-2',
  pdp_lightbox__nav__next: 'right-[-22px] max-[640px]:right-2',
  pdp_lightbox__img:
    'block w-full max-h-[calc(92vh-52px)] object-contain bg-bg max-[640px]:max-h-[calc(92vh-48px)]',
  pdp_lightbox__caption:
    'px-4 py-3.5 font-body text-[11px] font-semibold tracking-[0.1em] uppercase text-center border-t-[3px] border-ink text-muted',
  // --- Boş sepet mesajı (cart.js) ---
  cart_empty:
    'py-16 px-6 text-center border-[3px] border-ink shadow-brutal bg-surface',
  cart_empty__title: 'font-body text-xl font-bold uppercase mb-2',
  cart_empty__text: 'text-muted mb-6',
  btn: 'inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color]',
  btn__fill: 'bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5',
};
