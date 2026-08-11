@extends('user.layout')
@section('title', $product->title)

@section('content')
@php
  $placeholder = asset('user/assets/foto5.jpeg');
  $waLink = $siteSetting->whatsappLink('Merhaba, "' . $product->title . '" ürünü için teklif almak istiyorum.');
  $propertyGroups = $product->propertyGroups->filter(fn ($g) => $g->items->isNotEmpty());
  $descriptionPlain = trim(strip_tags(html_entity_decode($product->description ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')));
  $descriptionExcerptLimit = 280;
  $descriptionExcerpt = $descriptionPlain !== '' ? \Illuminate\Support\Str::limit($descriptionPlain, $descriptionExcerptLimit) : '';
  $hasFullDescription = $descriptionPlain !== '';
  $showDescriptionTeaser = $hasFullDescription && (strlen($descriptionPlain) > $descriptionExcerptLimit || str_contains($product->description ?? '', '<'));
@endphp

<main class="py-8 pb-20">
  <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
    <nav data-i5="product-page__breadcrumb" class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-8 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum">
      <a href="{{ route('index') }}">Anasayfa</a>
      <span class="opacity-[0.4]">/</span>
      <a href="{{ route('shops') }}">Tüm Ürünler</a>
      @if ($categoryFilter)
        <span class="opacity-[0.4]">/</span>
        <a href="{{ route('shops', ['kategori' => $categoryFilter->slug]) }}">{{ $categoryFilter->name }}</a>
      @endif
      <span class="opacity-[0.4]">/</span>
      <span>{{ $product->title }}</span>
    </nav>

    @if (session('success'))
    <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
    @endif

    @if ($product->stock_count > 0)
      @php
        $canAddToCart = auth()->check()
            && auth()->user()->type === \App\Enums\UserType::USER
            && auth()->user()->status === \App\Enums\Status::ACTIVE;
        $loginRedirect = parse_url(route('shopDetail', $product->slug), PHP_URL_PATH) ?: '/';
      @endphp
      <form method="post" action="{{ route('cartStore') }}" data-pdp-cart-form data-product-form data-product-id="{{ $product->id }}"
            class="grid grid-cols-1 gap-10 min-[960px]:grid-cols-2 min-[960px]:gap-x-14 min-[960px]:gap-y-4 min-[960px]:items-start" data-i5="product-page__grid">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">

        <div class="order-1 min-w-0 max-w-full min-[960px]:col-start-1 min-[960px]:row-start-1 min-[960px]:row-span-2" data-i5-pdp-gallery>
          <div class="relative aspect-[4/5] border-[3px] border-ink shadow-brutal bg-surface overflow-hidden mb-3" data-i5="pdp-gallery__main">
            @if ($product->introduction_status)
              <span class="absolute top-0 left-0 z-[2] px-3 py-2 bg-badge text-badge-fg font-body text-[10px] font-bold uppercase tracking-[0.06em] border-b-2 border-r-2 border-action/25" data-i5="pdp-gallery__badge">Yeni</span>
            @elseif ($product->featured_status)
              <span class="absolute top-0 left-0 z-[2] px-3 py-2 bg-accent text-on-dark font-body text-[10px] font-bold uppercase tracking-[0.06em] border-b-2 border-r-2 border-ink/25" data-i5="pdp-gallery__badge">Öne Çıkan</span>
            @endif

            @if ($product->images->count() > 1)
              <button type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 left-3 hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)]" data-i5-pdp-prev aria-label="Önceki görsel">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
              </button>
              <button type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 right-3 hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)]" data-i5-pdp-next aria-label="Sonraki görsel">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
              </button>
            @endif

            <div class="flex h-full overflow-x-auto scroll-smooth snap-x snap-mandatory overscroll-x-contain [scrollbar-width:none] focus:outline-none" data-i5-pdp-track tabindex="0" aria-label="Ürün görselleri" data-i5="pdp-gallery__track">
              @forelse ($product->images as $index => $image)
                <div class="shrink-0 basis-full w-full h-full snap-start snap-always cursor-zoom-in [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:select-none" data-i5="pdp-gallery__slide">
                  <img src="{{ $image->url }}" alt="{{ $product->title }} — görsel {{ $index + 1 }}">
                </div>
              @empty
                <div class="shrink-0 basis-full w-full h-full snap-start [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="pdp-gallery__slide">
                  <img src="{{ $placeholder }}" alt="{{ $product->title }}">
                </div>
              @endforelse
            </div>
          </div>

          @if ($product->images->count() > 1)
            <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-4 md:grid-cols-5" data-i5="pdp-gallery__thumbs">
              @foreach ($product->images as $index => $image)
                <button type="button"
                        class="aspect-square border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg p-0 cursor-pointer opacity-65 transition-all hover:opacity-100 hover:-translate-x-px hover:-translate-y-px hover:shadow-brutal {{ $index === 0 ? 'is-active opacity-100 ring-2 ring-accent ring-offset-2' : '' }} [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover"
                        aria-label="Görsel {{ $index + 1 }}" @if($index === 0) aria-current="true" @endif data-i5="pdp-gallery__thumb">
                  <img src="{{ $image->url }}" alt="">
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <div class="order-2 contents min-[960px]:grid min-[960px]:col-start-2 min-[960px]:row-start-1 min-[960px]:row-span-2 min-[960px]:gap-3 min-[960px]:content-start min-[960px]:auto-rows-min min-[960px]:pt-2">
        <div class="order-2 min-w-0 max-w-full min-[960px]:order-none" data-i5="pdp-info">
          @if ($product->category)
            <a href="{{ route('shops', ['kategori' => $categoryFilter?->slug ?? $product->category->slug]) }}"
               class="inline-block font-body text-[11px] font-bold tracking-[0.1em] uppercase text-accent mb-3 transition-colors hover:text-accent-dark" data-i5="pdp-info__category">
              {{ $product->category->name }}
            </a>
          @endif

          <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case mb-4">{{ $product->title }}</h1>

          <p class="font-body text-2xl font-bold mb-5" data-i5="pdp-info__price">
            <span data-pdp-base-price="{{ (float) $product->price }}" data-pdp-total-price>{{ number_format((float) $product->price, 0, ',', '.') }}₺</span>
          </p>

          @if ($hasFullDescription)
            <div class="mb-8 max-w-[520px]" data-i5="pdp-info__desc">
              <p class="m-0 text-muted leading-[1.7]">{{ $descriptionExcerpt }}</p>
              @if ($showDescriptionTeaser)
                <a href="#pdp-full-details"
                   data-pdp-view-all-details
                   class="mt-3 inline-flex items-center gap-2 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent transition-colors hover:text-accent-dark">
                  Tüm özellikleri görüntüle
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </a>
              @endif
            </div>
          @endif

          <div class="grid grid-cols-3 gap-3 max-[599px]:grid-cols-1 mb-0" data-i5="pdp-trust">
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">3–5 Gün</strong>
              <span class="text-[11px] text-muted">Teslimat</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">500₺+</strong>
              <span class="text-[11px] text-muted">Ücretsiz Kargo</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">Prova</strong>
              <span class="text-[11px] text-muted">Dijital Onay</span>
            </div>
          </div>
        </div>

        <div class="order-4 min-w-0 max-w-full grid gap-3 mb-9 min-[960px]:order-none min-[960px]:mb-0" data-i5="pdp-actions">
          @if ($propertyGroups->isNotEmpty())
            <div class="border-[3px] border-ink bg-bg p-4" data-pdp-price-summary>
              <p class="mb-3 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Fiyat özeti</p>
              <div class="grid gap-2 text-sm">
                <div class="flex justify-between gap-3">
                  <span class="text-muted">Baz fiyat</span>
                  <span class="font-semibold text-ink" data-pdp-summary-base>{{ number_format((float) $product->price, 0, ',', '.') }}₺</span>
                </div>
                <div class="grid gap-1.5" data-pdp-summary-lines></div>
                <div class="flex justify-between gap-3 border-t-[3px] border-ink pt-3 mt-1">
                  <span class="font-body text-[13px] font-bold uppercase tracking-[0.04em] text-ink">Toplam</span>
                  <span class="font-body text-[18px] font-bold text-ink" data-pdp-summary-total>{{ number_format((float) $product->price, 0, ',', '.') }}₺</span>
                </div>
              </div>
            </div>
          @endif

          @if ($canAddToCart)
            <button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5" data-i5="btn--fill">Sepete Ekle</button>
          @else
            <a href="{{ route('loginPage', ['redirect' => $loginRedirect]) }}"
               data-pdp-login-cta
               class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5" data-i5="btn--fill">
              Giriş Yapıp Sepete Ekle
            </a>
          @endif

          @if ($waLink)
            <a href="{{ $waLink }}"
               class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink bg-surface text-ink shadow-ui hover:bg-hover"
               target="_blank" rel="noopener noreferrer" data-i5="btn--outline">Teklif İste</a>
          @endif
        </div>
        </div>

        @if ($propertyGroups->isNotEmpty())
          <div class="order-3 min-w-0 max-w-full border-t-[3px] border-ink pt-8 min-[960px]:order-4 min-[960px]:col-span-2 min-[960px]:col-start-1 min-[960px]:row-start-3" data-pdp-properties-slot>
            @include('user.partials.pdp-property-groups', [
              'propertyGroups' => $propertyGroups,
              'defaultPropertySelections' => $defaultPropertySelections,
              'layout' => 'slider',
            ])
          </div>
        @endif

        <section class="order-5 min-w-0 max-w-full border-t-[3px] border-ink pt-8 min-[960px]:col-span-2 min-[960px]:col-start-1 min-[960px]:row-start-4" id="pdp-full-details">
          <h2 class="mb-5 font-heading text-[18px] font-semibold text-ink normal-case scroll-mt-28">Ürün Detayları</h2>
          <div class="text-muted text-sm leading-[1.7] [&_p]:mb-2 [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:underline [&_h2]:mb-2 [&_h2]:mt-3 [&_h2]:text-ink [&_h2]:text-base [&_h2]:font-bold [&_h3]:mb-2 [&_h3]:mt-3 [&_h3]:text-ink [&_h3]:text-sm [&_h3]:font-bold" data-i5="pdp-accordion__body">
            @if ($product->description)
              {!! $product->description !!}
            @else
              <p>Bu ürün için detaylı açıklama henüz eklenmemiş.</p>
            @endif
            <ul class="mt-4 space-y-2 list-disc pl-4">
              <li>Ürün kodu: {{ $product->code }}</li>
              <li>Stok: {{ $product->stock_count }} adet</li>
            </ul>
          </div>

          <div class="mt-8 border-t-[3px] border-ink [&_details]:border-b-[3px] [&_details]:border-ink [&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-4 [&_summary]:py-[18px] [&_summary]:font-body [&_summary]:text-[13px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:tracking-[0.04em] [&_summary]:cursor-pointer [&_summary]:list-none [&_summary::-webkit-details-marker]:hidden [&_summary_svg]:shrink-0 [&_summary_svg]:transition-transform [&_details[open]_summary_svg]:rotate-180" data-i5="pdp-accordion">
            <details>
              <summary>
                Kargo &amp; Teslimat
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7]" data-i5="pdp-accordion__body">
                <p>{{ $siteSetting->shippingDetailText() }}</p>
              </div>
            </details>
          </div>
        </section>
      </form>
    @else
      <div class="grid gap-10 min-[960px]:grid-cols-2 min-[960px]:gap-14 min-[960px]:items-start" data-i5="product-page__grid">
        <div data-i5-pdp-gallery>
          <div class="relative aspect-[4/5] border-[3px] border-ink shadow-brutal bg-surface overflow-hidden mb-3" data-i5="pdp-gallery__main">
            @if ($product->introduction_status)
              <span class="absolute top-0 left-0 z-[2] px-3 py-2 bg-badge text-badge-fg font-body text-[10px] font-bold uppercase tracking-[0.06em] border-b-2 border-r-2 border-action/25" data-i5="pdp-gallery__badge">Yeni</span>
            @elseif ($product->featured_status)
              <span class="absolute top-0 left-0 z-[2] px-3 py-2 bg-accent text-on-dark font-body text-[10px] font-bold uppercase tracking-[0.06em] border-b-2 border-r-2 border-ink/25" data-i5="pdp-gallery__badge">Öne Çıkan</span>
            @endif

            @if ($product->images->count() > 1)
              <button type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 left-3 hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)]" data-i5-pdp-prev aria-label="Önceki görsel">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
              </button>
              <button type="button" class="absolute top-1/2 z-[3] flex items-center justify-center w-11 h-11 border-[3px] border-ink shadow-brutal-sm bg-white/95 -translate-y-1/2 right-3 hover:bg-surface hover:-translate-x-px hover:-translate-y-[calc(50%+1px)]" data-i5-pdp-next aria-label="Sonraki görsel">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
              </button>
            @endif

            <div class="flex h-full overflow-x-auto scroll-smooth snap-x snap-mandatory overscroll-x-contain [scrollbar-width:none] focus:outline-none" data-i5-pdp-track tabindex="0" aria-label="Ürün görselleri" data-i5="pdp-gallery__track">
              @forelse ($product->images as $index => $image)
                <div class="shrink-0 basis-full w-full h-full snap-start snap-always cursor-zoom-in [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover [&_img]:select-none" data-i5="pdp-gallery__slide">
                  <img src="{{ $image->url }}" alt="{{ $product->title }} — görsel {{ $index + 1 }}">
                </div>
              @empty
                <div class="shrink-0 basis-full w-full h-full snap-start [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="pdp-gallery__slide">
                  <img src="{{ $placeholder }}" alt="{{ $product->title }}">
                </div>
              @endforelse
            </div>
          </div>

          @if ($product->images->count() > 1)
            <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-4 md:grid-cols-5" data-i5="pdp-gallery__thumbs">
              @foreach ($product->images as $index => $image)
                <button type="button"
                        class="aspect-square border-[3px] border-ink shadow-brutal-sm overflow-hidden bg-bg p-0 cursor-pointer opacity-65 transition-all hover:opacity-100 hover:-translate-x-px hover:-translate-y-px hover:shadow-brutal {{ $index === 0 ? 'is-active opacity-100 ring-2 ring-accent ring-offset-2' : '' }} [&_img]:block [&_img]:w-full [&_img]:h-full [&_img]:object-cover"
                        aria-label="Görsel {{ $index + 1 }}" @if($index === 0) aria-current="true" @endif data-i5="pdp-gallery__thumb">
                  <img src="{{ $image->url }}" alt="">
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <div class="pt-2" data-i5="pdp-info">
          @if ($product->category)
            <a href="{{ route('shops', ['kategori' => $categoryFilter?->slug ?? $product->category->slug]) }}"
               class="inline-block font-body text-[11px] font-bold tracking-[0.1em] uppercase text-accent mb-3 transition-colors hover:text-accent-dark" data-i5="pdp-info__category">
              {{ $product->category->name }}
            </a>
          @endif

          <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case mb-4">{{ $product->title }}</h1>

          <p class="font-body text-2xl font-bold mb-5" data-i5="pdp-info__price">
            <span class="text-muted text-xl">Stokta yok</span>
          </p>

          @if ($hasFullDescription)
            <div class="mb-8 max-w-[520px]" data-i5="pdp-info__desc">
              <p class="m-0 text-muted leading-[1.7]">{{ $descriptionExcerpt }}</p>
              @if ($showDescriptionTeaser)
                <a href="#pdp-full-details"
                   data-pdp-view-all-details
                   class="mt-3 inline-flex items-center gap-2 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent transition-colors hover:text-accent-dark">
                  Tüm özellikleri görüntüle
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </a>
              @endif
            </div>
          @endif

          <div class="grid grid-cols-3 gap-3 mb-7 max-[599px]:grid-cols-1" data-i5="pdp-trust">
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">3–5 Gün</strong>
              <span class="text-[11px] text-muted">Teslimat</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">500₺+</strong>
              <span class="text-[11px] text-muted">Ücretsiz Kargo</span>
            </div>
            <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
              <strong class="block font-body text-[11px] font-bold uppercase mb-1">Prova</strong>
              <span class="text-[11px] text-muted">Dijital Onay</span>
            </div>
          </div>

          <div class="grid gap-3 mb-9" data-i5="pdp-actions">
            <span class="inline-flex items-center justify-center w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink bg-cream text-muted cursor-not-allowed">Stokta Yok</span>
            @if ($waLink)
              <a href="{{ $waLink }}"
                 class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-center border-[3px] border-ink bg-surface text-ink shadow-ui hover:bg-hover"
                 target="_blank" rel="noopener noreferrer" data-i5="btn--outline">Teklif İste</a>
            @endif
          </div>

          <div class="border-t-[3px] border-ink [&_details]:border-b-[3px] [&_details]:border-ink [&_summary]:flex [&_summary]:items-center [&_summary]:justify-between [&_summary]:gap-4 [&_summary]:py-[18px] [&_summary]:font-body [&_summary]:text-[13px] [&_summary]:font-bold [&_summary]:uppercase [&_summary]:tracking-[0.04em] [&_summary]:cursor-pointer [&_summary]:list-none [&_summary::-webkit-details-marker]:hidden [&_summary_svg]:shrink-0 [&_summary_svg]:transition-transform [&_details[open]_summary_svg]:rotate-180" data-i5="pdp-accordion">
            <details open id="pdp-full-details">
              <summary>
                Ürün Detayları
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7] scroll-mt-28 [&_p]:mb-2 [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:underline [&_h2]:mb-2 [&_h2]:mt-3 [&_h2]:text-ink [&_h2]:text-base [&_h2]:font-bold [&_h3]:mb-2 [&_h3]:mt-3 [&_h3]:text-ink [&_h3]:text-sm [&_h3]:font-bold" data-i5="pdp-accordion__body">
                @if ($product->description)
                  {!! $product->description !!}
                @else
                  <p>Bu ürün için detaylı açıklama henüz eklenmemiş.</p>
                @endif
                <ul class="mt-4 space-y-2 list-disc pl-4">
                  <li>Ürün kodu: {{ $product->code }}</li>
                </ul>
              </div>
            </details>
            <details>
              <summary>
                Kargo &amp; Teslimat
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
              </summary>
              <div class="pb-5 text-muted text-sm leading-[1.7]" data-i5="pdp-accordion__body">
                <p>{{ $siteSetting->shippingDetailText() }}</p>
              </div>
            </details>
          </div>
        </div>
      </div>
    @endif

    @if ($productReviews->isNotEmpty())
      <section class="pt-16 border-t-[3px] border-ink mt-16" aria-labelledby="urun-yorumlari-baslik" data-i5="pdp-reviews">
        <div class="flex flex-col gap-2 mb-8 min-[768px]:flex-row min-[768px]:items-end min-[768px]:justify-between">
          <h2 id="urun-yorumlari-baslik" class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case m-0">Müşteri Yorumları</h2>
          <p class="m-0 text-sm text-muted">{{ $productReviews->count() }} onaylı değerlendirme</p>
        </div>
        <div class="grid gap-5">
          @foreach ($productReviews as $review)
            @php
              $stars = (int) max(1, min(5, (int) round((float) $review->rating)));
            @endphp
            <article class="border-[3px] border-ink bg-surface p-5 md:p-6 shadow-brutal-sm">
              <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-3">
                <span class="text-[#c9a227] tracking-[0.08em]" aria-label="{{ $stars }} yıldız">{{ str_repeat('★', $stars) }}{{ str_repeat('☆', 5 - $stars) }}</span>
                <span class="font-body text-[13px] font-bold text-ink">{{ $review->user?->name ?? 'Müşteri' }}</span>
                <span class="font-body text-[12px] text-muted">{{ $review->created_at?->format('d.m.Y') }}</span>
              </div>
              <p class="m-0 text-sm leading-relaxed text-muted">{{ $review->content }}</p>
              @if ($review->images->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                  @foreach ($review->images as $reviewImage)
                    <a href="{{ $reviewImage->url }}" target="_blank" rel="noopener noreferrer" class="block h-20 w-20 overflow-hidden border-[3px] border-ink bg-bg">
                      <img src="{{ $reviewImage->url }}" alt="Yorum görseli" class="h-full w-full object-cover" loading="lazy">
                    </a>
                  @endforeach
                </div>
              @endif
            </article>
          @endforeach
        </div>
      </section>
    @endif

    @if ($relatedProducts->isNotEmpty())
      <section class="pt-16 border-t-[3px] border-ink mt-16 overflow-hidden [&_article.reveal]:opacity-100 [&_article.reveal]:translate-y-0" data-i5="pdp-related">
        <div class="flex flex-col gap-5 mb-7 min-[768px]:flex-row min-[768px]:items-end min-[768px]:justify-between">
          <h2 class="font-heading text-section-title font-semibold leading-[1.15] tracking-[-0.02em] normal-case">Bunları da Beğenebilirsiniz</h2>
          <div class="flex items-center gap-3" data-i5="carousel-nav">
            <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-prev aria-label="Önceki ürünler">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="flex items-center justify-center w-11 h-11 border-[3px] border-ink bg-surface shadow-brutal-sm cursor-pointer shrink-0 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal" data-i5-carousel-next aria-label="Sonraki ürünler">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
          </div>
        </div>
        <div class="w-full min-[768px]:pl-[max(20px,calc((100vw-1280px)/2+20px))] min-[768px]:pr-[max(20px,calc((100vw-1280px)/2+20px))]">
          <div class="flex gap-4 overflow-x-auto overflow-y-hidden snap-x snap-mandatory scroll-smooth [scrollbar-width:none] px-5 pb-1 min-[768px]:px-0" data-i5-product-carousel data-i5="carousel">
            @foreach ($relatedProducts as $relatedProduct)
              @php
                $mainImage = $relatedProduct->images->first();
                $altImage = $relatedProduct->images->skip(1)->first();
              @endphp
              <article class="relative max-[767px]:snap-start shrink-0 basis-[calc(50%-8px)] min-w-0 md:basis-[calc(33.333%-11px)] lg:basis-[calc(25%-12px)] group/card" data-i5="carousel__item">
                <a href="{{ route('shopDetail', $relatedProduct->slug) }}" class="block">
                  <div class="relative aspect-square border-[3px] border-ink bg-surface overflow-hidden shadow-brutal-sm group-hover/card:shadow-brutal w-full" data-i5="product__media">
                    <img class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $altImage ? 'group-hover/card:opacity-0' : '' }}"
                         src="{{ $mainImage?->url ?? $placeholder }}" alt="{{ $relatedProduct->title }}">
                    @if ($altImage)
                      <img class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 opacity-0 group-hover/card:opacity-100"
                           src="{{ $altImage->url }}" alt="">
                    @endif
                  </div>
                  <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5">{{ $relatedProduct->title }}</h3>
                  <p class="font-body text-sm font-medium text-ink m-0 px-4 pb-4 pt-1">
                    {{ number_format((float) $relatedProduct->price, 0, ',', '.') }}₺
                  </p>
                </a>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif
  </div>
</main>

<script>
  (function () {
    const form = document.querySelector('[data-pdp-cart-form]');
    const priceEl = document.querySelector('[data-pdp-total-price]');
    if (!form || !priceEl) return;

    const base = parseFloat(priceEl.getAttribute('data-pdp-base-price') || '0') || 0;
    const linesEl = form.querySelector('[data-pdp-summary-lines]');
    const summaryTotalEl = form.querySelector('[data-pdp-summary-total]');
    const storageKey = 'pdp_props_' + (form.getAttribute('data-product-id') || '');

    const formatTry = (value) => {
      return new Intl.NumberFormat('tr-TR', { maximumFractionDigits: 0 }).format(value) + '₺';
    };

    const collectSelections = () => {
      const selected = [];
      form.querySelectorAll('[data-property-group]').forEach((group) => {
        const groupTitle = group.getAttribute('data-group-title') || 'Özellik';
        group.querySelectorAll('[data-property-price]:checked').forEach((input) => {
          selected.push({
            group: groupTitle,
            title: input.getAttribute('data-property-title') || '',
            price: parseFloat(input.getAttribute('data-property-price') || '0') || 0,
            name: input.name,
            value: input.value,
          });
        });
      });
      return selected;
    };

    const refresh = () => {
      const selected = collectSelections();
      const addon = selected.reduce((sum, row) => sum + row.price, 0);
      const total = base + addon;

      priceEl.textContent = formatTry(total);

      if (summaryTotalEl) {
        summaryTotalEl.textContent = formatTry(total);
      }

      if (linesEl) {
        if (!selected.length) {
          linesEl.innerHTML = '<p class="text-[12px] text-muted m-0">Henüz ek özellik seçilmedi.</p>';
        } else {
          const escapeHtml = (value) => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

          linesEl.innerHTML = selected.map((row) => {
            const priceText = row.price > 0
              ? '+' + formatTry(row.price)
              : '+0₺';
            return '<div class="flex justify-between gap-3">' +
              '<span class="text-muted min-w-0"><span class="font-semibold text-ink/70">' + escapeHtml(row.group) + ':</span> ' + escapeHtml(row.title) + '</span>' +
              '<span class="font-semibold text-ink whitespace-nowrap">' + priceText + '</span>' +
              '</div>';
          }).join('');
        }
      }
    };

    const persistSelections = () => {
      try {
        const payload = {};
        form.querySelectorAll('[data-property-price]:checked').forEach((input) => {
          if (input.type === 'checkbox') {
            if (!payload[input.name]) payload[input.name] = [];
            payload[input.name].push(input.value);
          } else {
            payload[input.name] = input.value;
          }
        });
        sessionStorage.setItem(storageKey, JSON.stringify(payload));
      } catch (e) {}
    };

    const restoreSelections = () => {
      try {
        const raw = sessionStorage.getItem(storageKey);
        if (!raw) return;
        const payload = JSON.parse(raw);
        Object.keys(payload || {}).forEach((name) => {
          const value = payload[name];
          if (Array.isArray(value)) {
            value.forEach((v) => {
              const input = form.querySelector('input[name="' + name + '"][value="' + v + '"]');
              if (input) input.checked = true;
            });
          } else {
            const input = form.querySelector('input[name="' + name + '"][value="' + value + '"]');
            if (input) input.checked = true;
          }
        });
      } catch (e) {}
    };

    form.addEventListener('change', () => {
      persistSelections();
      refresh();
    });

    form.addEventListener('submit', (event) => {
      const groups = form.querySelectorAll('[data-required-multiple="1"]');
      for (const group of groups) {
        if (!group.querySelector('input[type="checkbox"]:checked')) {
          event.preventDefault();
          const title = (group.getAttribute('data-group-title') || 'Özellik').trim();
          alert(title + ' için en az bir seçenek seçmelisiniz.');
          return;
        }
      }
      try { sessionStorage.removeItem(storageKey); } catch (e) {}
    });

    const loginCta = form.querySelector('[data-pdp-login-cta]');
    if (loginCta) {
      loginCta.addEventListener('click', () => {
        persistSelections();
      });
    }

    restoreSelections();
    refresh();
  })();

  (function () {
    const link = document.querySelector('[data-pdp-view-all-details]');
    const target = document.getElementById('pdp-full-details');
    if (!link || !target) return;

    link.addEventListener('click', (event) => {
      event.preventDefault();
      const section = document.getElementById('pdp-full-details');
      if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  })();
</script>
<script src="{{ asset('user/js/product.js') }}"></script>
@endsection
