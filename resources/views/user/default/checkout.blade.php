@extends('user.layout')
@section('title','Ödeme')
@section('content')
@php
  $placeholder = asset('user/assets/foto5.jpeg');
  $selectedAddressId = (int) old('address_id', $addresses->first()?->id);
@endphp
<main class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="{{ route('cart') }}">Sepet</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Ödeme</span>
      </nav>

      <div class="mb-8 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="checkout-page__head">
        <h1>Ödeme</h1>
        <p class="mt-2.5 text-sm text-muted font-semibold">Teslimat adresinizi seçin ve ödemeyi tamamlayın</p>
      </div>

      @if (session('error'))
      <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <form id="checkout-form" action="{{ route('checkoutStore') }}" method="post" class="grid gap-10 min-[960px]:grid-cols-[1fr_380px] min-[960px]:gap-12 min-[960px]:items-start">
        @csrf

        <div class="grid gap-6">
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Teslimat Adresi</h2>
            </div>
            <div class="p-5 grid gap-3">
              @foreach ($addresses as $address)
              <label class="flex gap-4 p-4 border-[3px] border-ink cursor-pointer transition-[background,box-shadow] hover:bg-hover has-[:checked]:border-accent has-[:checked]:shadow-brutal-sm" data-i5="checkout-address">
                <input type="radio" name="address_id" value="{{ $address->id }}" class="mt-1 accent-accent" @checked($selectedAddressId === $address->id) required>
                <div class="flex-1 text-sm leading-relaxed">
                  <p class="font-body text-xs font-bold uppercase tracking-[0.06em] mb-1">{{ $address->title }}</p>
                  <p class="font-semibold text-ink mb-1">{{ $user->name }}</p>
                  <p class="text-muted">{{ $address->content }}</p>
                  <p class="text-muted">{{ $address->county?->name }}, {{ $address->city?->name }}</p>
                  @if ($user->phone)
                  <p class="mt-2 font-semibold text-ink">{{ $user->phone }}</p>
                  @endif
                </div>
              </label>
              @endforeach
              @error('address_id')<span class="text-xs text-announce">{{ $message }}</span>@enderror
              <a href="{{ route('addressCreatePage') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-accent underline underline-offset-[3px] hover:text-ink">+ Yeni adres ekle</a>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Sipariş Notu</h2>
            </div>
            <div class="p-5">
              <textarea name="note" rows="3" class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[15px] bg-surface outline-none focus:shadow-brutal-sm" placeholder="Teslimat veya baskı ile ilgili notunuz (opsiyonel)">{{ old('note') }}</textarea>
              @error('note')<span class="text-xs text-announce">{{ $message }}</span>@enderror
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Ödeme Yöntemi</h2>
            </div>
            <div class="p-5 grid gap-4">
              <label class="flex items-center gap-3 p-4 border-[3px] border-accent bg-accent/5 cursor-pointer" data-i5="checkout-payment">
                <input type="radio" name="payment_method" value="card" checked class="accent-accent">
                <span class="font-body text-sm font-bold uppercase tracking-[0.04em]">Kredi / Banka Kartı</span>
              </label>
              <div id="checkout-card-fields" class="grid gap-3 min-[640px]:grid-cols-2 p-4 border-[3px] border-dashed border-ink bg-bg">
                <div class="min-[640px]:col-span-2">
                  <label class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">Kart Numarası</label>
                  <input type="text" value="**** **** **** 4242" disabled class="w-full px-3.5 py-[13px] border-[3px] border-ink bg-surface text-muted">
                </div>
                <div>
                  <label class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">Son Kullanma</label>
                  <input type="text" value="12/28" disabled class="w-full px-3.5 py-[13px] border-[3px] border-ink bg-surface text-muted">
                </div>
                <div>
                  <label class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">CVV</label>
                  <input type="text" value="***" disabled class="w-full px-3.5 py-[13px] border-[3px] border-ink bg-surface text-muted">
                </div>
              </div>
              <p class="text-xs text-muted">Bu aşamada gerçek ödeme alınmaz; ödeme simülasyonu ile siparişiniz oluşturulur.</p>
            </div>
          </section>
        </div>

        <aside class="border-[3px] border-ink shadow-brutal bg-surface p-7 min-[960px]:sticky min-[960px]:top-[calc(var(--spacing-announce)+80px)]" data-i5="checkout-summary">
          <h2 class="font-body text-[1.1rem] font-bold uppercase mb-6 pb-4 border-b-[3px] border-ink">Sipariş Özeti</h2>

          <div class="grid gap-4 mb-6">
            @foreach ($cartItems as $item)
            @php
              $product = $item->product;
              $lineTotal = (float) $product->price * $item->quantity;
            @endphp
            <div class="grid grid-cols-[56px_1fr_auto] gap-3 items-center text-sm">
              <div class="border-[3px] border-ink aspect-square overflow-hidden bg-bg [&_img]:w-full [&_img]:h-full [&_img]:object-cover">
                <img src="{{ $product->images->first()?->url ?? $placeholder }}" alt="{{ $product->title }}">
              </div>
              <div>
                <p class="font-semibold leading-snug">{{ $product->title }}</p>
                <p class="text-muted text-xs">{{ $item->quantity }} adet</p>
              </div>
              <span class="font-bold whitespace-nowrap">{{ number_format($lineTotal, 0, ',', '.') }} ₺</span>
            </div>
            @endforeach
          </div>

          @if ($shippingFree)
          <div class="mb-6 p-4 bg-bg border-[3px] border-ink text-xs font-semibold uppercase tracking-[0.04em] text-accent">Ücretsiz kargo kazandınız!</div>
          @elseif ($shippingRemaining > 0)
          <div class="mb-6 p-4 bg-bg border-[3px] border-ink text-xs font-semibold uppercase tracking-[0.04em]">Ücretsiz kargo için {{ number_format($shippingRemaining, 0, ',', '.') }} ₺ daha ekleyin</div>
          @endif

          <div class="flex justify-between gap-4 text-sm mb-3 text-muted">
            <span>Ara Toplam</span>
            <span>{{ number_format($subtotal, 0, ',', '.') }} ₺</span>
          </div>
          @if ($discountApplied)
          <div class="flex justify-between gap-4 text-sm mb-3 text-accent">
            <span>İndirim</span>
            <span>-{{ number_format($discountAmount, 0, ',', '.') }} ₺</span>
          </div>
          @endif
          <div class="flex justify-between gap-4 text-sm mb-3 text-muted">
            <span>Kargo</span>
            <span>{{ $shippingFree ? 'Ücretsiz' : number_format($shippingCost, 0, ',', '.').' ₺' }}</span>
          </div>
          <div class="flex justify-between gap-4 font-body text-xl font-bold mt-4 pt-4 border-t-[3px] border-ink">
            <span>Toplam</span>
            <span>{{ number_format($total, 0, ',', '.') }} ₺</span>
          </div>

          <button type="submit" data-i5="checkout-submit" class="mt-6 w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5 transition-[transform,box-shadow,background-color]">
            Ödeme Yap
          </button>
          <a href="{{ route('cart') }}" class="block text-center mt-4 text-[13px] font-semibold text-muted underline underline-offset-[3px] hover:text-accent">Sepete Dön</a>
        </aside>
      </form>
    </div>
  </main>
@endsection
