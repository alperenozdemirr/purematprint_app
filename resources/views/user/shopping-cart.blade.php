@extends('user.layout')
@section('title','Sepetim')
@section('content')
@php
  $placeholder = asset('user/assets/foto5.jpeg');
@endphp
<main>
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <div class="mb-10 pt-2 [&_[data-i5='breadcrumb']]:mb-7 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="cart-page__head">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
          <a href="{{ route('index') }}">Anasayfa</a>
          <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
          <span>Sepet</span>
        </nav>
        <h1>Sepetiniz</h1>
        <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="cart-page__count">{{ $totalQty }} ürün</p>
      </div>

      @if (session('success'))
      <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
      @endif
      @if (session('error'))
      <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <div class="grid gap-10 min-[960px]:grid-cols-[1fr_380px] min-[960px]:gap-12 min-[960px]:items-start" data-i5="cart-layout">
        <div id="cart-items">
          @forelse ($cartItems as $item)
            @php
              $product = $item->product;
              $mainImage = $product->images->first();
              $unitPrice = (float) $product->price;
              $lineTotal = $unitPrice * $item->quantity;
            @endphp
            <article class="grid grid-cols-[96px_1fr] gap-4 py-6 border-b-[3px] border-ink min-[640px]:grid-cols-[120px_1fr_auto] min-[640px]:gap-6 min-[640px]:items-center" data-unit-price="{{ (int) $unitPrice }}" data-i5="cart-item">
              <a href="{{ route('shopDetail', $product->slug) }}" class="block border-[3px] border-ink shadow-brutal-sm aspect-[3/4] overflow-hidden bg-bg [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="cart-item__img">
                <img src="{{ $mainImage?->url ?? $placeholder }}" alt="{{ $product->title }}">
              </a>
              <div>
                <a href="{{ route('shopDetail', $product->slug) }}" class="block font-heading text-card-title font-semibold leading-[1.35] normal-case mb-1.5 transition-colors hover:text-accent" data-i5="cart-item__name">{{ $product->title }}</a>
                <p class="text-sm text-muted mb-4" data-i5="cart-item__price">{{ number_format($unitPrice, 0, ',', '.') }} ₺ / adet</p>
                <div class="flex items-center justify-between gap-4 flex-wrap" data-i5="cart-item__actions">
                  <form method="post" action="{{ route('cartUpdate') }}" class="inline-flex items-stretch border-[3px] border-ink shadow-brutal-sm [&_button]:w-9 [&_button]:flex [&_button]:shrink-0 [&_button]:items-center [&_button]:justify-center [&_button]:text-base [&_button]:font-semibold [&_button]:bg-surface [&_button]:cursor-pointer [&_button]:transition-colors hover:[&_button:not(:disabled)]:bg-hover [&_button:disabled]:opacity-35 [&_button:disabled]:cursor-not-allowed [&_input]:w-11 [&_input]:min-w-11 [&_input]:shrink-0 [&_input]:text-center [&_input]:border-x-[3px] [&_input]:border-x-ink [&_input]:text-[13px] [&_input]:font-semibold [&_input]:outline-none [&_input]:bg-surface [&_input]:appearance-none [&_input::-webkit-outer-spin-button]:appearance-none [&_input::-webkit-inner-spin-button]:appearance-none [&_input]:[-moz-appearance:textfield]" data-i5="cart-qty">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" aria-label="Azalt" @disabled($item->quantity <= 1)>−</button>
                    <input type="number" value="{{ $item->quantity }}" min="1" max="{{ $product->stock_count }}" aria-label="Adet" readonly>
                    <button type="submit" name="quantity" value="{{ min($product->stock_count, $item->quantity + 1) }}" aria-label="Artır" @disabled($item->quantity >= $product->stock_count)>+</button>
                  </form>
                  <a href="{{ route('cartDelete', $item->id) }}" class="text-xs font-semibold uppercase tracking-[0.04em] text-muted underline underline-offset-[3px] transition-colors hover:text-ink" data-i5="cart-item__remove">Kaldır</a>
                </div>
              </div>
              <p class="font-body text-base font-bold text-right whitespace-nowrap max-[639px]:col-span-full max-[639px]:text-left max-[639px]:pt-1" data-i5="cart-item__total">{{ number_format($lineTotal, 0, ',', '.') }} ₺</p>
            </article>
          @empty
            <div class="border-[3px] border-ink bg-surface px-6 py-14 text-center shadow-brutal-sm" data-i5="cart-empty">
              <svg class="w-12 h-12 mx-auto mb-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
              </svg>
              <h2 class="font-body text-xl font-bold uppercase mb-2">Sepetiniz boş</h2>
              <p class="text-muted mb-6">Henüz sepetinize ürün eklemediniz.</p>
              <a href="{{ route('shops') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Alışverişe Başla</a>
            </div>
          @endforelse
        </div>

        <aside class="border-[3px] border-ink shadow-brutal bg-surface p-7 min-[960px]:sticky min-[960px]:top-[calc(var(--spacing-announce)+80px)] [&_h2]:font-body [&_h2]:text-[1.1rem] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:mb-6 [&_h2]:pb-4 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="cart-summary">
          <h2>Sipariş Özeti</h2>
          @if ($cartItems->isNotEmpty())
          <div class="mb-6 p-4 bg-bg border-[3px] border-ink [&_p]:text-xs [&_p]:font-semibold [&_p]:uppercase [&_p]:tracking-[0.04em] [&_p]:mb-2.5 [&_p.is-free]:text-accent [&_p.is-free]:font-semibold" data-i5="cart-shipping-bar">
            @if ($shippingFree)
            <p class="is-free">Ücretsiz kargo kazandınız!</p>
            @elseif ($shippingRemaining > 0)
            <p>Ücretsiz kargo için {{ number_format($shippingRemaining, 0, ',', '.') }} ₺ daha ekleyin</p>
            @endif
          </div>
          @endif
          <div class="flex justify-between gap-4 text-sm mb-3 [&_span:first-child]:text-muted" data-i5="cart-summary__row">
            <span>Ara Toplam</span>
            <span data-cart-subtotal>{{ number_format($subtotal, 0, ',', '.') }} ₺</span>
          </div>
          @if ($discountApplied)
          <div class="flex justify-between gap-4 text-sm mb-3 text-accent" data-i5="cart-summary__row">
            <span>İndirim</span>
            <span>-{{ number_format($discountAmount, 0, ',', '.') }} ₺</span>
          </div>
          @endif
          <div class="flex justify-between gap-4 text-sm mb-3 [&_span:first-child]:text-muted" data-i5="cart-summary__row">
            <span>Kargo</span>
            <span data-cart-shipping>
              @if ($cartItems->isEmpty())
                —
              @elseif ($shippingFree)
                Ücretsiz
              @else
                {{ number_format($shippingCost, 0, ',', '.') }} ₺
              @endif
            </span>
          </div>
          <div data-i5="cart-summary__row--total" data-i5-tags="cart-summary__row cart-summary__row--total" class="flex justify-between gap-4 text-sm mb-3 font-body text-xl font-bold mt-4 pt-4 border-t-[3px] border-ink [&_span:first-child]:text-muted">
            <span>Toplam</span>
            <span data-cart-total>{{ number_format($total, 0, ',', '.') }} ₺</span>
          </div>
          <p class="text-xs text-muted leading-normal my-3 mb-6" data-i5="cart-summary__note">Fiyatlara KDV dahildir.</p>
          <div class="grid gap-3 [&_[data-i5='btn']]:w-full [&_[data-i5='btn']]:justify-center [&_[data-i5='btn']]:text-center" data-i5="cart-summary__actions">
            @if ($cartItems->isNotEmpty())
            <a data-i5="btn--fill" data-i5-tags="btn btn--fill" href="{{ route('checkout') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">Ödemeye Geç</a>
            @endif
            <a href="{{ route('shops') }}" class="block text-center text-[13px] font-semibold text-muted underline underline-offset-[3px] transition-colors hover:text-accent" data-i5="cart-summary__continue">Alışverişe Devam Et</a>
          </div>
        </aside>
      </div>

      <section class="mt-16 mb-16 pt-12 border-t-[3px] border-ink" data-i5="cart-trust">
        <div class="grid gap-4 min-[768px]:grid-cols-3 min-[768px]:gap-5" data-i5="cart-trust__grid">
          <div class="p-6 border-[3px] border-ink shadow-brutal-sm bg-surface text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[13px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1.5 [&_span]:text-[13px] [&_span]:text-muted" data-i5="cart-trust__item">
            <strong>Güvenli Ödeme</strong>
            <span>256-bit SSL şifreleme</span>
          </div>
          <div class="p-6 border-[3px] border-ink shadow-brutal-sm bg-surface text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[13px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1.5 [&_span]:text-[13px] [&_span]:text-muted" data-i5="cart-trust__item">
            <strong>Hızlı Teslimat</strong>
            <span>3–5 iş günü</span>
          </div>
          <div class="p-6 border-[3px] border-ink shadow-brutal-sm bg-surface text-center [&_strong]:block [&_strong]:font-body [&_strong]:text-[13px] [&_strong]:font-bold [&_strong]:uppercase [&_strong]:mb-1.5 [&_span]:text-[13px] [&_span]:text-muted" data-i5="cart-trust__item">
            <strong>Kalite Garantisi</strong>
            <span>Memnuniyet garantili</span>
          </div>
        </div>
      </section>
    </div>
  </main>
@endsection
