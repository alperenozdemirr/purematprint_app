@extends('user.layout')
@section('title','Anasayfa')
@section('content')
 <!-- Hero -->
  <section data-i5="hero--cover" data-i5-tags="hero hero--cover" class="relative overflow-hidden border-b-[3px] border-ink">
    <div class="absolute inset-0 z-0 bg-dark" aria-hidden="true" data-i5="hero__media">
      <img src="{{asset('user')}}/assets/foto.jpeg" alt="" class="h-full w-full object-cover object-center">
      <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-b from-ink/45 via-ink/55 to-ink/80" aria-hidden="true"></div>
    </div>
    <div class="relative z-[2] flex min-h-[clamp(440px,72vh,760px)] w-full items-end py-14 pb-16 min-[768px]:py-[72px] min-[768px]:pb-20" data-i5="hero__overlay">
      <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
        <div class="max-w-[560px]" data-i5="hero__copy">
          <h1 class="mb-5 font-heading text-hero-title font-bold leading-[0.98] tracking-[-0.03em] text-white normal-case" data-i5="hero__title">Markanı<span class="block text-white opacity-90">yükselt.</span></h1>
          <p class="mb-7 max-w-[480px] text-[17px] text-[rgba(255,252,247,0.88)]" data-i5="hero__desc">Ajanslar ve tabelacılar için cesur baskı çözümleri. Kartvizitten dev tabelaya — her detay düşünülmüş, her proje kusursuz.</p>
          <div class="flex flex-wrap gap-3" data-i5="hero__actions">
            <a data-i5="btn--fill" data-i5-tags="btn btn--fill" href="{{ route('collectionList') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Koleksiyonu Keşfet →</a>
            <a data-i5="btn--light" data-i5-tags="btn btn--outline btn--light" href="{{ route('shops', ['siralama' => 'featured']) }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-white bg-transparent text-white shadow-none hover:bg-white/10">Çok Satanlar</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  @if ($tickerCompanies->isNotEmpty())
  <!-- Ticker -->
  <div class="overflow-hidden border-b-[3px] border-ink bg-dark text-on-dark py-3.5" data-i5="ticker">
    <div class="flex items-center w-max will-change-transform" data-i5-ticker data-i5="ticker__track">
      @foreach ($tickerCompanies as $company)
      <span class="shrink-0 font-body text-sm font-semibold uppercase tracking-[0.08em] whitespace-nowrap" data-i5="ticker__item">{{ $company->name }}</span>
      @endforeach
    </div>
  </div>
  @endif

  <!-- Best Sellers band -->
  <section data-i5="carousel-section" data-i5-tags="band section--white carousel-section" class="pt-14 mb-14 overflow-hidden min-[768px]:pt-[72px] min-[768px]:mb-[72px] bg-surface" id="cok-satanlar">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="band-head reveal" class="flex items-center justify-center gap-4 md:gap-6 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-prev aria-label="Önceki" data-i5="carousel-nav__btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <h2 class="font-heading m-0 text-center text-section-title font-semibold" data-i5="band-head__title">Çok Satanlar</h2>
        <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-next aria-label="Sonraki" data-i5="carousel-nav__btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        </button>
      </div>
      <p data-i5="reveal" data-i5-tags="band-head__more reveal" class="text-center my-4 mb-7 md:mb-9 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0"><a href="{{ route('shops', ['siralama' => 'featured']) }}" class="font-body text-[13px] font-bold uppercase tracking-[0.06em] underline underline-offset-4 hover:text-accent" data-i5="link">Tümünü gör →</a></p>
    </div>
    <div data-i5="band__track-wrap" data-i5-tags="carousel-wrap band__track-wrap" class="w-full max-w-none p-0 m-0 overflow-x-auto overflow-y-hidden border-t-[3px] border-b-[3px] border-ink bg-surface [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
      <div data-i5="band__track" data-i5-tags="carousel band__track" class="flex gap-0 p-0 overflow-y-visible snap-none scroll-smooth overscroll-x-contain overscroll-y-auto [scrollbar-width:none] max-[767px]:[touch-action:pan-x_pan-y]" data-i5-product-carousel>
        @php $placeholder = asset('user/assets/foto5.jpeg'); @endphp
        @forelse ($bestsellerProducts as $product)
          @php
            $mainImage = $product->images->first();
            $altImage = $product->images->skip(1)->first();
            $canAddToCart = auth()->check()
                && auth()->user()->type === \App\Enums\UserType::USER
                && auth()->user()->status === \App\Enums\Status::ACTIVE;
          @endphp
          <article data-i5="carousel__item" data-i5-tags="product" class="relative shrink-0 min-w-0 border-r-[3px] border-ink bg-surface last:border-r-0 flex-[0_0_calc(100vw/2.1)] min-[640px]:flex-[0_0_calc(100vw/3.15)] min-[1024px]:flex-[0_0_calc(100vw/4.15)] min-[1440px]:flex-[0_0_calc(100vw/5.1)] group/card max-sm:flex-[0_0_82vw] max-sm:max-w-[82vw]">
            <div class="relative aspect-square w-full overflow-hidden bg-surface border-b-[3px] border-ink shadow-none" data-i5="product__media">
              <a href="{{ route('shopDetail', $product->slug) }}" class="block absolute inset-0 z-[1]">
                @if ($product->introduction_status)
                  <span class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-action/15 leading-none" data-i5="product__badge">Yeni</span>
                @elseif ($product->featured_status)
                  <span class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-accent text-on-dark font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-ink/15 leading-none" data-i5="product__badge">Öne Çıkan</span>
                @endif
                <img data-i5="product__img--main" data-i5-tags="product__img product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out {{ $altImage ? 'group-hover/card:opacity-0' : '' }}" src="{{ $mainImage?->url ?? $placeholder }}" alt="{{ $product->title }}">
                @if ($altImage)
                  <img data-i5="product__img--alt" data-i5-tags="product__img product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100" src="{{ $altImage->url }}" alt="">
                @endif
              </a>
              @if ($product->stock_count > 0)
                @if ($canAddToCart)
                  <form method="post" action="{{ route('cartStore') }}" class="absolute bottom-0 inset-x-0 z-[3]">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</button>
                  </form>
                @else
                  <a href="{{ route('loginPage') }}" class="absolute bottom-0 inset-x-0 z-[3] block px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</a>
                @endif
              @endif
            </div>
            <a href="{{ route('shopDetail', $product->slug) }}" class="block no-underline text-inherit">
              <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5 pb-1.5 max-sm:px-[18px] max-sm:pt-4 max-sm:pb-2 max-sm:text-[17px]" data-i5="product__name">{{ $product->title }}</h3>
              <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1 max-sm:px-[18px] max-sm:pb-[18px] max-sm:text-[15px]" data-i5="product__price">
                @if ($product->stock_count === 0)
                  <span class="text-muted">Stokta yok</span>
                @else
                  {{ number_format((float) $product->price, 0, ',', '.') }}₺
                @endif
              </p>
            </a>
          </article>
        @empty
          <div class="flex shrink-0 items-center justify-center min-h-[280px] px-8 border-r-[3px] border-ink bg-surface flex-[0_0_100%]">
            <p class="font-body text-sm font-semibold text-muted">Henüz öne çıkan ürün bulunmuyor.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Welcome gallery — G&W "Crafted for a Warmer Welcome" -->
  <section class="py-12 pb-16 bg-bg min-[768px]:py-16 min-[768px]:pb-20" id="karsilama" data-i5="welcome">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <h2 data-i5="reveal" data-i5-tags="welcome__title reveal" class="font-heading text-[clamp(1.75rem,4vw,2.75rem)] font-bold leading-[1.08] tracking-[-0.03em] text-center text-balance max-w-[16ch] mx-auto mb-8 min-[768px]:max-w-none min-[768px]:mb-10 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">Sıcak Bir Karşılama İçin Tasarlandı</h2>
    </div>
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="welcome__grid reveal" class="grid gap-3 min-[768px]:grid-cols-2 min-[768px]:gap-4 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        @forelse ($welcomeBanners as $banner)
          <a href="{{ $banner->redirect_url ?: '#' }}" class="relative block overflow-hidden bg-dark min-h-[280px] min-[768px]:min-h-[420px] after:absolute after:inset-0 after:bg-gradient-to-t after:from-ink/40 after:to-transparent after:pointer-events-none group/tile" data-i5="welcome__tile">
            <div class="absolute inset-0" data-i5="welcome__media">
              @if ($banner->image)
                <img class="block w-full h-full object-cover transition-transform duration-500 ease-out group-hover/tile:scale-105" src="{{ $banner->image->url }}" alt="{{ $banner->button_label }}" loading="lazy">
              @else
                <div class="flex h-full w-full items-center justify-center bg-hover text-muted font-body text-sm">Görsel yok</div>
              @endif
            </div>
            <span class="absolute left-4 bottom-4 z-[1] inline-flex items-center px-[18px] py-3 font-body text-[11px] font-bold tracking-[0.1em] uppercase text-ink bg-white/95 border-2 border-ink transition-all duration-200 min-[768px]:left-6 min-[768px]:bottom-6 min-[768px]:px-[22px] min-[768px]:py-3.5 min-[768px]:text-xs group-hover/tile:bg-action group-hover/tile:text-on-dark group-hover/tile:-translate-y-0.5" data-i5="welcome__cta">{{ $banner->button_label }}</span>
          </a>
        @empty
          <div class="col-span-full flex min-h-[280px] items-center justify-center border-[3px] border-ink bg-surface px-6 py-10 text-center min-[768px]:min-h-[420px]">
            <p class="font-body text-sm font-semibold text-muted">Henüz banner eklenmemiş.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Spotlight — G&W image + quote + press mark -->
  <section class="py-16 border-t border-ink/12 border-b border-ink/12 bg-bg min-[768px]:py-[88px]" data-i5="spotlight">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="spotlight__grid reveal" class="grid gap-10 items-center min-[900px]:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] min-[900px]:gap-x-[72px] min-[900px]:gap-y-14 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <div class="max-w-[700px]" data-i5="spotlight__media">
          <img class="block w-full h-auto" src="{{asset('user')}}/assets/foto1.jpeg" alt="A-Frame tabela sokak uygulaması" loading="lazy">
        </div>
        <div class="text-center min-[900px]:text-center" data-i5="spotlight__body">
          <h2 class="font-heading mb-7 text-[clamp(1.5rem,3.5vw,2.5rem)] font-bold leading-tight tracking-[-0.03em] text-balance" data-i5="spotlight__quote">"İşlevsel, minimal ve oyunbaz baskı ürünleri"</h2>
          <div class="inline-flex items-center justify-center max-w-[160px] mx-auto text-ink opacity-85" data-i5="spotlight__mark"><span aria-label="Design Milk" class="font-heading text-2xl font-normal tracking-wide uppercase">DESIGN</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Video band — G&W background video -->
  <section class="relative overflow-hidden bg-dark" aria-label="Atölye" data-i5="video-band">
    <div class="relative overflow-hidden h-[clamp(320px,50vw,560px)] after:absolute after:inset-0 after:bg-ink/20 after:pointer-events-none" data-i5="video-band__media"><img class="block w-full h-full object-cover" src="{{asset('user')}}/assets/foto2.jpeg" alt="PureMatPrint atölye" loading="lazy">
    </div>
  </section>

  <!-- Process -->
  <section class="py-[72px] border-b-[3px] border-ink" id="surec" data-i5="section">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="section-head reveal" class="mb-10 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <h2 class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case" data-i5="title">Dört adımda teslimat</h2>
      </div>
      <div class="grid gap-0 border-[3px] border-ink shadow-brutal min-[768px]:grid-cols-2 min-[1024px]:grid-cols-4" data-i5="process__grid">
        <div data-i5="reveal" data-i5-tags="process__step reveal" class="p-7 bg-surface border-b-[3px] border-ink min-[768px]:border-b-0 min-[768px]:border-r-[3px] hover:bg-hover opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <div class="font-body text-[56px] font-bold leading-none text-accent mb-3" data-i5="process__num">01</div>
          <h3 class="font-heading text-base font-bold uppercase tracking-tight mb-2">Brief & Tasarım</h3>
          <p class="text-sm text-muted leading-relaxed">İhtiyacınızı dinliyor, dosyalarınızı kontrol ediyoruz.</p>
        </div>
        <div data-i5="reveal" data-i5-tags="process__step reveal" class="p-7 bg-surface border-b-[3px] border-ink min-[768px]:border-b-0 min-[768px]:border-r-[3px] hover:bg-hover opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <div class="font-body text-[56px] font-bold leading-none text-accent mb-3" data-i5="process__num">02</div>
          <h3 class="font-heading text-base font-bold uppercase tracking-tight mb-2">Prova & Onay</h3>
          <p class="text-sm text-muted leading-relaxed">Dijital prova gönderiyor, onayınızı alıyoruz.</p>
        </div>
        <div data-i5="reveal" data-i5-tags="process__step reveal" class="p-7 bg-surface border-b-[3px] border-ink min-[768px]:border-b-0 min-[768px]:border-r-[3px] hover:bg-hover opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <div class="font-body text-[56px] font-bold leading-none text-accent mb-3" data-i5="process__num">03</div>
          <h3 class="font-heading text-base font-bold uppercase tracking-tight mb-2">Üretim</h3>
          <p class="text-sm text-muted leading-relaxed">CNC kesim, UV baskı ve el işçiliği.</p>
        </div>
        <div data-i5="reveal" data-i5-tags="process__step reveal" class="p-7 bg-surface border-b-[3px] border-ink min-[768px]:border-b-0 min-[768px]:border-r-[3px] hover:bg-hover opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
          <div class="font-body text-[56px] font-bold leading-none text-accent mb-3" data-i5="process__num">04</div>
          <h3 class="font-heading text-base font-bold uppercase tracking-tight mb-2">Teslimat</h3>
          <p class="text-sm text-muted leading-relaxed">Kargo veya profesyonel montaj ekibi.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Müşteri yorumları -->
  <section class="py-16 bg-bg border-t border-ink/12 min-[768px]:py-[88px]" id="yorumlar" aria-labelledby="yorumlar-baslik" data-i5="testimonials">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="testimonials__head reveal" class="flex items-center justify-center gap-4 mb-10 min-[768px]:gap-6 min-[768px]:mb-12 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-testimonials-prev aria-label="Önceki yorum" data-i5="carousel-nav__btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <h2 data-i5="testimonials__title" data-i5-tags="title testimonials__title" class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case m-0 text-center text-[clamp(1.35rem,3.5vw,2rem)]" id="yorumlar-baslik">Müşteri yorumları</h2>
        <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-testimonials-next aria-label="Sonraki yorum" data-i5="carousel-nav__btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        </button>
      </div>
      <div data-i5="reveal" data-i5-tags="testimonials__slider reveal" class="relative opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0" data-i5-testimonials>
        <div class="grid relative" data-i5="testimonials__slides">
          <article class="col-start-1 row-start-1 grid gap-8 items-center opacity-0 invisible transition-[opacity,visibility] duration-[450ms] ease-out pointer-events-none min-[900px]:grid-cols-[1fr_minmax(280px,0.85fr)] min-[900px]:gap-x-16 min-[900px]:gap-y-12 is-active [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:pointer-events-auto [&.is-active]:z-[1]" data-i5="testimonials__slide">
            <div >
              <div class="text-[#c9a227] text-lg tracking-[0.12em] mb-5" aria-hidden="true" data-i5="testimonials__stars">★★★★★</div>
              <blockquote class="font-heading mb-6 text-[clamp(1.35rem,3.2vw,2.25rem)] font-bold leading-[1.15] tracking-[-0.02em] text-balance" data-i5="testimonials__quote">İşlerine gerçekten özen gösteriyorlar. Mağazamızın varlığını müşteriler içeri girmeden yükseltiyor.</blockquote>
              <p class="m-0 text-sm text-muted tracking-[0.02em]" data-i5="testimonials__author">— Elif Yılmaz, Studio Noir</p>
            </div>
            <div class="aspect-[4/3] overflow-hidden bg-surface" data-i5="testimonials__media">
              <img src="{{asset('user')}}/assets/foto1.jpeg" alt="LED lightbox vitrin uygulaması" loading="lazy">
            </div>
          </article>
          <article class="col-start-1 row-start-1 grid gap-8 items-center opacity-0 invisible transition-[opacity,visibility] duration-[450ms] ease-out pointer-events-none min-[900px]:grid-cols-[1fr_minmax(280px,0.85fr)] min-[900px]:gap-x-16 min-[900px]:gap-y-12 [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:pointer-events-auto [&.is-active]:z-[1]" data-i5="testimonials__slide">
            <div >
              <div class="text-[#c9a227] text-lg tracking-[0.12em] mb-5" aria-hidden="true" data-i5="testimonials__stars">★★★★★</div>
              <blockquote class="font-heading mb-6 text-[clamp(1.35rem,3.2vw,2.25rem)] font-bold leading-[1.15] tracking-[-0.02em] text-balance" data-i5="testimonials__quote">Zamansız sadelik ve profesyonellik bir arada. Her projede güvenle yönlendiriyorum.</blockquote>
              <p class="m-0 text-sm text-muted tracking-[0.02em]" data-i5="testimonials__author">— Can Demir, Demir Mimarlık</p>
            </div>
            <div class="aspect-[4/3] overflow-hidden bg-surface" data-i5="testimonials__media">
              <img src="{{asset('user')}}/assets/WhatsApp Image 2026-06-27 at 00.28.44.jpeg" alt="Açık hava tabela uygulaması" loading="lazy">
            </div>
          </article>
          <article class="col-start-1 row-start-1 grid gap-8 items-center opacity-0 invisible transition-[opacity,visibility] duration-[450ms] ease-out pointer-events-none min-[900px]:grid-cols-[1fr_minmax(280px,0.85fr)] min-[900px]:gap-x-16 min-[900px]:gap-y-12 [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:pointer-events-auto [&.is-active]:z-[1]" data-i5="testimonials__slide">
            <div >
              <div class="text-[#c9a227] text-lg tracking-[0.12em] mb-5" aria-hidden="true" data-i5="testimonials__stars">★★★★★</div>
              <blockquote class="font-heading mb-6 text-[clamp(1.35rem,3.2vw,2.25rem)] font-bold leading-[1.15] tracking-[-0.02em] text-balance" data-i5="testimonials__quote">Yoğun dönemlerde PureMatPrint'e güveniyoruz. Hızlı prova, kusursuz teslimat.</blockquote>
              <p class="m-0 text-sm text-muted tracking-[0.02em]" data-i5="testimonials__author">— Zeynep Kaya, Kaya Coffee</p>
            </div>
            <div class="aspect-[4/3] overflow-hidden bg-surface" data-i5="testimonials__media">
              <img src="{{asset('user')}}/assets/foto2.jpeg" alt="Roll-up banner ve display uygulaması" loading="lazy">
            </div>
          </article>
          <article class="col-start-1 row-start-1 grid gap-8 items-center opacity-0 invisible transition-[opacity,visibility] duration-[450ms] ease-out pointer-events-none min-[900px]:grid-cols-[1fr_minmax(280px,0.85fr)] min-[900px]:gap-x-16 min-[900px]:gap-y-12 [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:pointer-events-auto [&.is-active]:z-[1]" data-i5="testimonials__slide">
            <div >
              <div class="text-[#c9a227] text-lg tracking-[0.12em] mb-5" aria-hidden="true" data-i5="testimonials__stars">★★★★★</div>
              <blockquote class="font-heading mb-6 text-[clamp(1.35rem,3.2vw,2.25rem)] font-bold leading-[1.15] tracking-[-0.02em] text-balance" data-i5="testimonials__quote">Marka kimliğimizi mekâna taşıyan güvenilir bir ortak. Detaylara verdikleri önem fark yaratıyor.</blockquote>
              <p class="m-0 text-sm text-muted tracking-[0.02em]" data-i5="testimonials__author">— Selin Arslan, Atlas Reklam</p>
            </div>
            <div class="aspect-[4/3] overflow-hidden bg-surface" data-i5="testimonials__media">
              <img src="{{asset('user')}}/assets/foto3.jpeg" alt="Kafe tezgâhı display uygulaması" loading="lazy">
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- Press / Partners -->
  <section class="py-16 border-b border-ink/12 bg-bg md:py-20" aria-label="Referans markalar" data-i5="press">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <ul data-i5="reveal" data-i5-tags="press__grid reveal" class="grid grid-cols-2 gap-x-6 gap-y-10 list-none m-0 p-0 min-[768px]:grid-cols-4 min-[768px]:gap-8 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <li class="flex flex-col items-center text-center gap-4" data-i5="press__item">
          <div class="flex items-center justify-center w-full max-w-[160px] min-h-12 text-ink" aria-hidden="true" data-i5="press__mark">
            <svg viewBox="0 0 160 40" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Studio Noir">
              <text x="80" y="28" text-anchor="middle" font-family="Georgia, 'Times New Roman', serif" font-size="22" font-weight="400" letter-spacing="0.18em" fill="currentColor">STUDIO NOIR</text>
            </svg>
          </div>
          <p class="m-0 max-w-[22ch] text-sm leading-normal text-muted italic min-[768px]:text-[15px] min-[768px]:max-w-[24ch]" data-i5="press__quote">"Vitrinimizi müşteriler içeri girmeden yükseltiyor."</p>
        </li>
        <li class="flex flex-col items-center text-center gap-4" data-i5="press__item">
          <div class="flex items-center justify-center w-full max-w-[160px] min-h-12 text-ink" aria-hidden="true" data-i5="press__mark">
            <svg viewBox="0 0 160 40" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Demir Mimarlık">
              <text x="80" y="18" text-anchor="middle" font-family="'Space Grotesk', sans-serif" font-size="11" font-weight="700" letter-spacing="0.28em" fill="currentColor">DEMIR</text>
              <text x="80" y="34" text-anchor="middle" font-family="'Space Grotesk', sans-serif" font-size="11" font-weight="700" letter-spacing="0.28em" fill="currentColor">MIMARLIK</text>
            </svg>
          </div>
          <p class="m-0 max-w-[22ch] text-sm leading-normal text-muted italic min-[768px]:text-[15px] min-[768px]:max-w-[24ch]" data-i5="press__quote">"Zamansız sadelik ve profesyonellik bir arada."</p>
        </li>
        <li class="flex flex-col items-center text-center gap-4" data-i5="press__item">
          <div class="flex items-center justify-center w-full max-w-[160px] min-h-12 text-ink" aria-hidden="true" data-i5="press__mark">
            <svg viewBox="0 0 160 40" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Kaya Coffee">
              <text x="80" y="28" text-anchor="middle" font-family="'IBM Plex Sans', sans-serif" font-size="24" font-style="italic" font-weight="500" fill="currentColor">Kaya Coffee</text>
            </svg>
          </div>
          <p class="m-0 max-w-[22ch] text-sm leading-normal text-muted italic min-[768px]:text-[15px] min-[768px]:max-w-[24ch]" data-i5="press__quote">"Yoğun dönemlerde hızlı prova, kusursuz teslimat."</p>
        </li>
        <li class="flex flex-col items-center text-center gap-4" data-i5="press__item">
          <div class="flex items-center justify-center w-full max-w-[160px] min-h-12 text-ink" aria-hidden="true" data-i5="press__mark">
            <svg viewBox="0 0 160 40" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Atlas Reklam">
              <text x="80" y="28" text-anchor="middle" font-family="'Space Grotesk', sans-serif" font-size="20" font-weight="700" letter-spacing="0.06em" fill="currentColor">ATLAS</text>
            </svg>
          </div>
          <p class="m-0 max-w-[22ch] text-sm leading-normal text-muted italic min-[768px]:text-[15px] min-[768px]:max-w-[24ch]" data-i5="press__quote">"Marka kimliğimizi mekâna taşıyan güvenilir ortak."</p>
        </li>
      </ul>
    </div>
  </section>

  <!-- Team Note -->
  <section class="py-[72px] border-b border-ink/12 bg-bg min-[768px]:py-[88px]" id="ekip-notu" data-i5="note">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div data-i5="reveal" data-i5-tags="note__grid reveal" class="grid gap-10 items-center min-[768px]:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)] min-[768px]:gap-x-16 min-[768px]:gap-y-12 min-[1024px]:gap-x-20 min-[1024px]:gap-y-14 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <div class="w-full max-w-[450px] mx-auto min-[768px]:mx-0" data-i5="note__media">
          <img class="block w-full h-auto" src="{{asset('user')}}/assets/foto2.jpeg" alt="PureMatPrint ekibi atölyede çalışırken">
        </div>
        <div >
          <h2 class="font-heading text-[clamp(1.75rem,3.5vw,2.5rem)] font-bold leading-[1.1] tracking-[-0.03em] mb-6 normal-case" data-i5="note__title">PureMatPrint'ten Bir Not</h2>
          <div class="text-base leading-relaxed text-ink" data-i5="note__text">
            <p class="mb-4">Yaratıcı mekanlar için sade baskı ürünleri üretmek amacıyla kurulduk. Bugün hâlâ aynı tutkuyla çalışıyor, sizin gibi markaların mekanlarını yükseltmelerine yardımcı oluyoruz. Sorularınız olursa bizimle iletişime geçmekten çekinmeyin.</p>
            <p class="mb-0">Keyifli projeler,</p>
          </div>
          <div class="inline-block w-[180px] mt-5 text-ink" aria-hidden="true" data-i5="note__signature">
            <svg class="block w-full h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 220 56" fill="none" aria-hidden="true">
              <path d="M12 38 C28 14, 44 42, 58 26 C72 10, 86 12, 98 28 C108 40, 118 44, 132 40 C146 36, 152 18, 168 16 C182 14, 192 30, 206 36" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M24 46 C40 42, 58 48, 78 44 C98 40, 118 46, 142 42 C162 38, 184 44, 204 40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.75"/>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SEO content — G&W accordion -->
  <section class="pt-16 pb-24 border-t border-ink/12 bg-bg min-[768px]:pb-28" id="hakkimizda-ozet" data-i5="seo">
    <div class="w-full max-w-[760px] mx-auto px-5 lg:px-8" data-i5="container">
      <details data-i5="reveal" data-i5-tags="seo__details reveal" class="group/seo border-t border-ink/12 opacity-0 translate-y-6 transition-all duration-700 [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0">
        <summary class="flex items-center justify-between gap-4 py-5 list-none cursor-pointer text-ink font-heading text-[clamp(1.1rem,2.5vw,1.5rem)] font-bold leading-tight tracking-tight">Tabela, Baskı &amp; Kurumsal Kimlik<svg class="shrink-0 transition-transform group-open/seo:rotate-180" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </summary>
        <div data-i5="seo__body" class="pb-6 text-[15px] leading-relaxed text-muted">
          <p class="mb-4">PureMatPrint'te tabelanın yalnızca bir işaret değil, mekânınızın ilk izlenimi olduğuna inanıyoruz. Köşe kafeden butik stüdyoya, yoğun perakende noktasından kurumsal ofise — ürünlerimiz günlük etkileşimleri yükseltmek için tasarlandı. Sade, işlevsel ve minimal; her parça çevresine uyum sağlarken markanızı öne çıkarır.</p>
          <p class="mb-4">İstanbul merkezli atölyemiz, kullanımı keyifli ve uzun ömürlü nesneler üretme tutkusuyla kuruldu. Bugün tabela, menü display ve kurumsal baskı alanlarında mekânları daha davetkâr hale getiren çözümler sunuyoruz.</p>
          <h3 class="font-heading mt-6 mb-2.5 text-base font-bold text-ink uppercase tracking-wide">Sizin İçin Konuşan Tabelalar</h3>
          <p class="mb-4">Vitrin tabelası, kaldırım A-frame'i veya tezgâh üstü yönlendirme — doğru tabela fark yaratır. Açık hava tabelalarından LED lightbox'lara, roll-up banner'lardan magnet display'lere kadar geniş koleksiyonumuz hava koşullarına dayanıklı malzemelerle üretilir.</p>
          <h3 class="font-heading mt-6 mb-2.5 text-base font-bold text-ink uppercase tracking-wide">Pratik Menü &amp; Display Çözümleri</h3>
          <p class="mb-4">Menüler okunması kadar güncellenmesi de kolay olmalı. Magnetic panolar, duvar montajlı menüler ve tezgâh üstü display'ler bilgiyi taze ve erişilebilir tutar — stilinizden ödün vermeden.</p>
          <h3 class="font-heading mt-6 mb-2.5 text-base font-bold text-ink uppercase tracking-wide">Neden PureMatPrint?</h3>
          <ul class="pl-5 list-disc">
            <li class="mt-2">Zamansız sadelik — her mekâna uyum sağlayan tasarım</li>
            <li class="mt-2">Dayanıklılık — iç ve dış mekân için uzun ömürlü malzemeler</li>
            <li class="mt-2">Proje odaklı — ajanslar ve markalar için uçtan uca destek</li>
            <li class="mt-2">Müşteri önceliği — iletişimi kolay, etkili ve keyifli kılan ürünler</li>
          </ul>
        </div>
      </details>
    </div>
  </section>
@endsection