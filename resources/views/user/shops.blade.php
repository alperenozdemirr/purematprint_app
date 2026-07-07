@extends('user.layout')
@section('title','Tüm Ürünler')
@section('content')
  <!-- Ürün kataloğu — index5.js: kategori filtreleri + sayfalama -->
  <section class="py-10 pb-20 pt-6 min-[768px]:pt-7 [&_[data-i5='pagination']]:mt-10 [&_[data-i5='pagination']]:pt-2" data-i5="shop--catalog" data-i5-tags="shop shop--catalog">
    <div class="w-full max-w-site min-[1024px]:max-w-catalog mx-auto px-5 lg:px-8" data-i5="container">
      <div class="mb-5" data-i5="shop__head">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum" data-i5="breadcrumb">
          <a href="index.html">Anasayfa</a>
          <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
          <span>Tüm Ürünler</span>
        </nav>
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case" data-i5="shop__title">Tüm Ürünler</h1>
      </div>
      <div class="flex flex-col gap-5 mb-8 pb-6 border-b-[3px] border-ink min-[900px]:flex-row min-[900px]:items-center min-[900px]:justify-between min-[900px]:gap-6" data-i5="shop__toolbar">
        <div class="flex flex-wrap gap-2" id="product-filters" role="tablist" aria-label="Kategori filtreleri" data-i5="filters">
          <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 is-active [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-filter="all" role="tab" aria-selected="true" data-i5="filter">Tümü</button>
          <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-filter="tabela" role="tab" aria-selected="false" data-i5="filter">Tabela &amp; Afiş</button>
          <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-filter="kurumsal" role="tab" aria-selected="false" data-i5="filter">Kurumsal Kimlik</button>
          <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-filter="ambalaj" role="tab" aria-selected="false" data-i5="filter">Ambalaj</button>
          <button type="button" class="px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2 [&.is-active]:bg-action [&.is-active]:text-on-dark [&.is-active]:border-ink [&.is-active]:shadow-brutal-sm" data-filter="dijital" role="tab" aria-selected="false" data-i5="filter">Dijital Baskı</button>
        </div>
        <div class="flex items-center gap-4 shrink-0 max-[899px]:w-full max-[899px]:shrink max-[899px]:justify-between max-[899px]:gap-3 max-[899px]:flex-col max-[899px]:items-stretch max-[899px]:gap-2.5" data-i5="shop__meta">
          <span class="text-[13px] font-semibold text-muted whitespace-nowrap" id="product-count" data-i5="shop__count">24 ürün</span>
          <div class="relative max-[899px]:flex-1 max-[899px]:min-w-0 after:absolute after:right-[14px] after:top-1/2 after:-translate-y-1/2 after:w-0 after:h-0 after:border-l-[4px] after:border-r-[4px] after:border-t-[5px] after:border-l-transparent after:border-r-transparent after:border-t-ink after:pointer-events-none max-[899px]:w-full max-[899px]:z-10 [&.is-open]:z-[60]" data-i5="shop__sort">
            <label for="product-sort" class="hidden">Sırala</label>
            <select id="product-sort" class="px-3.5 py-2.5 border-[3px] border-ink bg-surface font-body text-[13px] font-semibold text-ink shadow-brutal-sm cursor-pointer outline-none focus:shadow-brutal w-full min-w-0 max-w-full min-[900px]:w-auto pr-9 appearance-none max-[899px]:text-xs max-[899px]:leading-snug" aria-label="Sıralama">
              <option value="featured">Öne Çıkan</option>
              <option value="price-asc">Fiyat: Düşük → Yüksek</option>
              <option value="price-desc">Fiyat: Yüksek → Düşük</option>
              <option value="name">İsim: A → Z</option>
            </select>
          </div>
    </div>
      </div>

      <!-- ürün kartı — backend'de foreach ile çoğaltın -->
      <div class="grid grid-cols-2 gap-4 min-[768px]:grid-cols-3 min-[768px]:gap-5 min-[1024px]:grid-cols-4" id="product-grid" data-i5-product-grid data-i5-per-page="12" data-i5-rows="3" data-i5="products">
        <article data-i5="reveal" data-i5-tags="product reveal" class="relative opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-price="350" data-category="kurumsal">
          <a href="product.html" class="block">
            <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
              <span class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-action/15 leading-none" data-i5="product__badge">Yeni</span>
              <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/foto5.jpeg" alt="Premium Kartvizit">
              <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="">
              <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</span>
            </div>
            <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Premium Kartvizit</h3>
            <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">350₺'den başlayan</p>
          </a>
        </article>
        <article data-i5="reveal" data-i5-tags="product reveal" class="relative opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0 [&.is-page-hidden]:hidden [&.is-filter-hidden]:hidden" data-price="680" data-category="kurumsal">
          <a href="product.html" class="block">
            <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
              <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out group-hover/card:opacity-0" src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.43.jpeg" alt="Metal Kartvizit">
              <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{asset('user')}}/assets/foto5.jpeg" alt="">
              <span class="absolute bottom-0 inset-x-0 z-[3] px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</span>
            </div>
            <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">Metal Kartvizit</h3>
            <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">680₺'den başlayan</p>
          </a>
        </article>
        
        
        
      </div>

      <nav class="flex justify-center items-center gap-2 mt-9 flex-wrap" data-i5-pagination aria-label="Ürün sayfaları" hidden data-i5="pagination"></nav>
    </div>
  </section>

@endsection