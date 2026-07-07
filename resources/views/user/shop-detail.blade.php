@extends('user.layout')
@section('title','Anasayfa')
@section('content')
<main>
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav data-i5="product-page__breadcrumb" data-i5-tags="breadcrumb product-page__breadcrumb" class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent mb-8" aria-label="Konum">
        <a href="index.html">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="category.html?cat=kartvizit">Kartvizit &amp; Baskı</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Premium Kartvizit</span>
      </nav>

      <div class="grid gap-10 min-[960px]:grid-cols-2 min-[960px]:gap-14 min-[960px]:items-start" data-i5="product-page__grid">
        <div  data-i5-pdp-gallery>
          <div class="relative aspect-[4/5] border-[3px] border-ink shadow-brutal bg-surface overflow-hidden mb-3" data-i5="pdp-gallery__main">
            <span class="absolute top-0 left-0 z-[2] px-3 py-2 bg-badge text-badge-fg font-body text-[10px] font-bold uppercase tracking-[0.06em] border-b-2 border-r-2 border-action/25" data-i5="pdp-gallery__badge">Yeni</span>
            <button data-i5="pdp-gallery__nav--prev" data-i5-tags="pdp-gallery__nav pdp-gallery__nav--prev" type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 transition-[background,transform] hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)] left-3" data-i5-pdp-prev aria-label="Önceki görsel">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button data-i5="pdp-gallery__nav--next" data-i5-tags="pdp-gallery__nav pdp-gallery__nav--next" type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 transition-[background,transform] hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)] right-3" data-i5-pdp-next aria-label="Sonraki görsel">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="flex h-full overflow-x-auto scroll-smooth snap-x snap-mandatory overscroll-x-contain [scrollbar-width:none] focus:outline-none focus-visible:outline focus-visible:outline-[3px] focus-visible:outline-accent focus-visible:-outline-offset-3" data-i5-pdp-track tabindex="0" aria-label="Ürün görselleri" data-i5="pdp-gallery__track">
              <div class="shrink-0 basis-full w-full h-full snap-start snap-always cursor-zoom-in [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:select-none" data-i5="pdp-gallery__slide">
                <img src="{{asset('user')}}/assets/foto5.jpeg" alt="Premium Kartvizit — görsel 1">
              </div>
              <div class="shrink-0 basis-full w-full h-full snap-start snap-always cursor-zoom-in [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:select-none" data-i5="pdp-gallery__slide">
                <img src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="Premium Kartvizit — görsel 2">
              </div>
              <div class="shrink-0 basis-full w-full h-full snap-start snap-always cursor-zoom-in [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:select-none" data-i5="pdp-gallery__slide">
                <img src="{{asset('user')}}/assets/foto5.jpeg" alt="Premium Kartvizit — görsel 3">
              </div>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2.5" data-i5="pdp-gallery__thumbs">
            <button type="button" class="aspect-square border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg p-0 cursor-pointer opacity-65 transition-all hover:opacity-100 hover:-translate-x-px hover:-translate-y-px hover:shadow-brutal is-active [&.is-active]:opacity-100 [&.is-active]:ring-2 [&.is-active]:ring-accent [&.is-active]:ring-offset-2 [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover" aria-label="Görsel 1" aria-current="true" data-i5="pdp-gallery__thumb">
              <img src="{{asset('user')}}/assets/foto5.jpeg" alt="">
            </button>
            <button type="button" class="aspect-square border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg p-0 cursor-pointer opacity-65 transition-all hover:opacity-100 hover:-translate-x-px hover:-translate-y-px hover:shadow-brutal [&.is-active]:opacity-100 [&.is-active]:ring-2 [&.is-active]:ring-accent [&.is-active]:ring-offset-2 [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover" aria-label="Görsel 2" data-i5="pdp-gallery__thumb">
              <img src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="">
            </button>
            <button type="button" class="aspect-square border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg p-0 cursor-pointer opacity-65 transition-all hover:opacity-100 hover:-translate-x-px hover:-translate-y-px hover:shadow-brutal [&.is-active]:opacity-100 [&.is-active]:ring-2 [&.is-active]:ring-accent [&.is-active]:ring-offset-2 [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover" aria-label="Görsel 3" data-i5="pdp-gallery__thumb">
              <img src="{{asset('user')}}/assets/foto5.jpeg" alt="">
            </button>
          </div>
        </div>

        <div class="pt-2 [&_h1]:font-heading [&_h1]:text-pdp-title [&_h1]:font-semibold [&_h1]:leading-[1.08] [&_h1]:tracking-[-0.025em] [&_h1]:normal-case [&_h1]:mb-4" data-i5="pdp-info">
          <a href="category.html?cat=kartvizit" class="inline-block font-body text-[11px] font-bold tracking-[0.1em] uppercase text-accent mb-3 transition-colors hover:text-accent-dark" data-i5="pdp-info__category">Kartvizit</a>
          <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case">Premium Kartvizit</h1>
          <p class="font-body text-2xl font-bold mb-5 [&_.compare]:line-through [&_.compare]:opacity-45 [&_.compare]:text-[1.1rem] [&_.compare]:mr-2.5 [&_.sale]:text-accent" data-i5="pdp-info__price">350₺'den başlayan</p>
          <p class="text-muted leading-[1.7] mb-8 max-w-[520px]" data-i5="pdp-info__desc">350 gr mat veya parlak kuşe kağıda basılan, yuvarlatılmış köşeli premium kartvizitler. Markanızın ilk izlenimini güçlendiren zarif bir detay.</p>

          <div class="grid grid-cols-3 gap-3 mb-7 max-[599px]:grid-cols-1" data-i5="pdp-trust">
            <div class="p-3 border-[3px] border-ink bg-bg text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[11px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1 [&_span]:text-[11px] [&_span]:text-muted" data-i5="pdp-trust__item">
              <strong>3–5 Gün</strong>
              <span>Teslimat</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[11px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1 [&_span]:text-[11px] [&_span]:text-muted" data-i5="pdp-trust__item">
              <strong>500₺+</strong>
              <span>Ücretsiz Kargo</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[11px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1 [&_span]:text-[11px] [&_span]:text-muted" data-i5="pdp-trust__item">
              <strong>Prova</strong>
              <span>Dijital Onay</span>
            </div>
          </div>

          <div class="flex flex-col gap-6 py-7 border-t-[3px] border-b-[3px] border-ink mb-7" data-i5="pdp-options">
            <div class="flex flex-col gap-3 min-w-0 min-[540px]:grid min-[540px]:grid-cols-[104px_minmax(0,1fr)] min-[540px]:gap-x-5 min-[540px]:gap-y-3 min-[540px]:items-start" data-i5="pdp-option">
              <span class="block font-body text-[11px] font-bold tracking-[0.08em] uppercase leading-[1.35] m-0 min-[540px]:pt-[11px]" id="pdp-option-paper" data-i5="pdp-option__label">Kağıt</span>
              <div class="flex flex-wrap gap-2.5 min-w-0" role="group" aria-labelledby="pdp-option-paper" data-i5="pdp-option__values">
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 is-active [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">Mat Kuşe</button>
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">Parlak Kuşe</button>
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">Kraft</button>
              </div>
            </div>
            <div class="flex flex-col gap-3 min-w-0 min-[540px]:grid min-[540px]:grid-cols-[104px_minmax(0,1fr)] min-[540px]:gap-x-5 min-[540px]:gap-y-3 min-[540px]:items-start" data-i5="pdp-option">
              <span class="block font-body text-[11px] font-bold tracking-[0.08em] uppercase leading-[1.35] m-0 min-[540px]:pt-[11px]" id="pdp-option-qty" data-i5="pdp-option__label">Baskı Adedi</span>
              <div class="flex flex-wrap gap-2.5 min-w-0" role="group" aria-labelledby="pdp-option-qty" data-i5="pdp-option__values">
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 is-active [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">250</button>
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">500</button>
                <button type="button" class="px-3.5 py-2.5 font-body text-[13px] font-medium leading-tight whitespace-nowrap border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-i5="pdp-option__btn">1000</button>
              </div>
            </div>
          </div>

          <div class="grid gap-3 mb-9 [&_[data-i5='btn']]:w-full [&_[data-i5='btn']]:justify-center [&_[data-i5='btn']]:text-center" data-i5="pdp-actions">
            <a data-i5="btn--fill" data-i5-tags="btn btn--fill" href="cart.html" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Sepete Ekle</a>
            <a data-i5="btn--outline" data-i5-tags="btn btn--outline" href="https://wa.me/905321234567?text=Merhaba%2C%20%22Premium%20Kartvizit%22%20%C3%BCr%C3%BCn%C3%BC%20i%C3%A7in%20teklif%20almak%20istiyorum." class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover" target="_blank" rel="noopener noreferrer">Teklif İste</a>
          </div>

          <div class="border-t-[3px] border-ink [&_details]:border-b-[3px] [&_details]:border-ink [&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-4 [&_summary]:py-[18px] [&_summary]:font-body [&_summary]:text-[13px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:tracking-[0.04em] [&_summary]:cursor-pointer [&_summary]:list-none [&_summary::-webkit-details-marker]:hidden [&_summary_svg]:shrink-0 [&_summary_svg]:transition-transform [&_details[open]_summary_svg]:rotate-180" data-i5="pdp-accordion">
            <details open>
              <summary>
                Ürün Detayları
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7] [&_ul]:list-disc [&_ul]:pl-5 [&_li+li]:mt-2" data-i5="pdp-accordion__body">
                <ul class="space-y-2 list-disc pl-4">
                  <li>350 gr premium kuşe kağıt</li>
                  <li>Yuvarlatılmış köşe seçeneği</li>
                  <li>Mat veya parlak laminasyon</li>
                  <li>Standart boyut: 85 × 55 mm</li>
                  <li>3-5 iş günü teslimat</li>
                </ul>
              </div>
            </details>
            <details>
              <summary>
                Kargo &amp; Teslimat
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7] [&_ul]:list-disc [&_ul]:pl-5 [&_li+li]:mt-2" data-i5="pdp-accordion__body">
                <p>Standart siparişler 3–5 iş günü içinde kargoya verilir. 500₺ üzeri siparişlerde Türkiye geneli ücretsiz kargo uygulanır. Acil siparişler için WhatsApp üzerinden bize ulaşabilirsiniz.</p>
              </div>
            </details>
            <details>
              <summary>
                Baskı Dosyası Gereksinimleri
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7] [&_ul]:list-disc [&_ul]:pl-5 [&_li+li]:mt-2" data-i5="pdp-accordion__body">
                <p>PDF, AI veya EPS formatında, 300 DPI çözünürlükte, CMYK renk modunda dosyalar kabul edilir. Tasarım desteği için ekibimizle iletişime geçebilirsiniz.</p>
              </div>
            </details>
          </div>
        </div>
      </div>

      <section data-i5="carousel-section" data-i5-tags="pdp-related carousel-section" class="pt-16 border-t-[3px] border-ink mt-16 overflow-hidden [&_[data-i5='carousel-head']]:mb-7 [&_[data-i5='carousel-wrap']]:px-0 [&_[data-i5='carousel']]:px-0 [&_[data-i5='carousel']]:pb-1 [&_article.reveal]:opacity-100 [&_article.reveal]:translate-y-0 max-[767px]:[&_[data-i5='carousel']]:[touch-action:pan-x_pan-y] max-[767px]:[&_[data-i5='carousel']]:[scroll-padding-inline:0]">
        <div data-i5="reveal" data-i5-tags="carousel-head reveal" class="flex flex-col gap-5 mb-7 min-[768px]:flex-row min-[768px]:items-end min-[768px]:justify-between min-[768px]:gap-6 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <h2 data-i5="carousel-head__title" data-i5-tags="title carousel-head__title" class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case min-w-0">Bunları da Beğenebilirsiniz</h2>
          <div class="flex items-center gap-3" data-i5="carousel-nav">
            <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-prev aria-label="Önceki ürünler" data-i5="carousel-nav__btn">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-next aria-label="Sonraki ürünler" data-i5="carousel-nav__btn">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
          </div>
        </div>
        <div class="w-full min-[768px]:pl-[max(20px,calc((100vw-1280px)/2+20px))] min-[768px]:pr-[max(20px,calc((100vw-1280px)/2+20px))] min-[1024px]:pl-[max(32px,calc((100vw-1280px)/2+32px))] min-[1024px]:pr-[max(32px,calc((100vw-1280px)/2+32px))]" data-i5="carousel-wrap">
          <!-- ürün kartı — backend'de foreach ile çoğaltın -->
          <div class="flex gap-4 overflow-x-auto overflow-y-hidden snap-x snap-mandatory scroll-smooth overscroll-x-contain overscroll-y-auto [scrollbar-width:none] px-5 pb-1 max-[767px]:[touch-action:pan-x_pan-y] max-[767px]:snap-proximity min-[768px]:pl-0 min-[768px]:pr-0" data-i5-product-carousel data-i5="carousel">
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="kurumsal">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="Metal Kartvizit">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto5.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Metal Kartvizit</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">680₺'den başlayan</p>
              </a>
            </article>
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="tabela">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/foto.jpeg" alt="Roll-Up Banner">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto1.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Roll-Up Banner</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">890₺'den başlayan</p>
              </a>
            </article>
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="tabela">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <span data-i5="product__badge--sale" data-i5-tags="product__badge product__badge--sale" class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-action/15 leading-none bg-badge-sale text-on-dark border-action/15">İndirim</span>
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/foto1.jpeg" alt="Magnet Afiş Seti">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Magnet Afiş Seti</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">420₺'den başlayan</p>
              </a>
            </article>
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="kurumsal">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/foto4.jpeg" alt="Kurumsal Kimlik Seti">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto5.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Kurumsal Kimlik Seti</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">2.400₺'den başlayan</p>
              </a>
            </article>
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="tabela">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/foto5.jpeg" alt="LED Lightbox Tabela">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto4.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">LED Lightbox Tabela</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">3.200₺'den başlayan</p>
              </a>
            </article>
            <article data-i5="reveal" data-i5-tags="product carousel__item reveal" class="relative max-[767px]:snap-start shrink-0 grow-0 basis-[calc(50%-8px)] snap-start min-w-0 md:shrink-0 md:grow-0 md:basis-[calc(33.333%-11px)] lg:shrink-0 lg:grow-0 lg:basis-[calc(25%-12px)] opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-category="kurumsal">
              <a href="product.html" class="block">
                <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
                  <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.44.jpeg" alt="Antetli Kağıt Seti">
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto1.jpeg" alt="">
                  <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add" role="button" tabindex="0">Sepete Ekle</span>
                </div>
                <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Antetli Kağıt Seti</h3>
                <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">520₺'den başlayan</p>
              </a>
            </article>
          </div>
        </div>
      </section>
    </div>
  </main>
@endsection