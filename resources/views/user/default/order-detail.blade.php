@extends('user.layout')
@section('title','Sipariş Detay')
@section('content')
@php
  $authUser = auth()->user();
  $placeholder = asset('user/assets/foto5.jpeg');
  $totalQty = $order->details->sum('quantity');
  $statusClass = match ($order->status) {
      \App\Enums\OrderStatus::PREPARING => 'is-processing',
      \App\Enums\OrderStatus::SHIPPED => 'is-shipped',
      \App\Enums\OrderStatus::COMPLETED => 'is-delivered',
      \App\Enums\OrderStatus::CANCELLED => 'is-cancelled',
  };
  $steps = [
      ['label' => 'Sipariş Alındı', 'done' => true],
      ['label' => 'Hazırlanıyor', 'done' => in_array($order->status, [\App\Enums\OrderStatus::PREPARING, \App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::COMPLETED], true)],
      ['label' => 'Kargoya Verildi', 'done' => in_array($order->status, [\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::COMPLETED], true)],
      ['label' => 'Tamamlandı', 'done' => $order->status === \App\Enums\OrderStatus::COMPLETED],
  ];
  $currentStep = match ($order->status) {
      \App\Enums\OrderStatus::PREPARING => 1,
      \App\Enums\OrderStatus::SHIPPED => 2,
      \App\Enums\OrderStatus::COMPLETED => 3,
      default => 0,
  };
  $waText = rawurlencode('Merhaba, '.$order->code.' numaralı siparişim hakkında bilgi almak istiyorum.');
@endphp
<main id="order-detail-root" class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="{{ route('orderList') }}">Siparişlerim</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>{{ $order->code }}</span>
      </nav>

      @if (session('success'))
      <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
      @endif
      @if (session('error'))
      <div class="mb-5 p-3.5 border-[3px] border-announce bg-[rgba(182,29,15,0.06)] text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <div class="flex flex-wrap items-start justify-between gap-4 mb-5 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="order-detail__header">
        <div>
          <h1>{{ $order->code }}</h1>
          <p class="text-[13px] text-muted mt-1.5" data-i5="order-detail__meta">{{ $order->created_at->translatedFormat('j F Y H:i') }} · {{ $totalQty }} ürün · Kredi / Banka Kartı</p>
        </div>
        <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce {{ $statusClass }}" data-i5="order-card__status">{{ $order->status->label() }}</span>
      </div>

      <div class="flex flex-wrap items-center gap-2.5 mb-7 pb-6 border-b-[3px] border-ink" data-i5="order-detail__actions">
        <a data-i5="btn--outline" href="{{ route('shops') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">Tekrar Sipariş Ver</a>
        <a data-i5="btn--outline" href="https://wa.me/905321234567?text={{ $waText }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">WhatsApp Destek</a>
        <a href="{{ route('orderList') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-muted ml-auto transition-colors hover:text-accent" data-i5="order-detail__back">← Tüm Siparişler</a>
      </div>

      <div class="grid gap-6 min-[960px]:grid-cols-[1fr_340px] min-[960px]:items-start" data-i5="order-detail__layout">
        <div class="grid gap-6" data-i5="order-detail__main">
          @if ($order->status !== \App\Enums\OrderStatus::CANCELLED)
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Sipariş Durumu</h2>
            <div class="grid gap-0" data-i5="order-timeline">
              @foreach ($steps as $index => $step)
              <div class="flex gap-4 relative pb-6 last:pb-0 {{ $step['done'] ? 'is-done' : '' }} {{ $currentStep === $index ? 'is-current' : '' }} group/step [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:left-[11px] [&:not(:last-child)]:after:top-6 [&:not(:last-child)]:after:bottom-0 [&:not(:last-child)]:after:w-0.5 [&:not(:last-child)]:after:bg-ink/20" data-i5="order-timeline__step">
                <div class="w-6 h-6 shrink-0 flex items-center justify-center border-2 border-hover bg-surface text-[11px] font-bold text-muted relative z-[1] group-[.is-done]/step:bg-action group-[.is-done]/step:border-ink group-[.is-done]/step:text-on-dark group-[.is-current]/step:bg-accent group-[.is-current]/step:border-ink group-[.is-current]/step:text-on-dark" data-i5="order-timeline__dot">{{ $step['done'] ? '✓' : ($index + 1) }}</div>
                <span class="text-sm font-semibold pt-0.5 group-[.is-current]/step:text-accent" data-i5="order-timeline__label">{{ $step['label'] }}</span>
              </div>
              @endforeach
            </div>
          </section>
          @endif

          <section id="order-review" class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink scroll-mt-24" data-i5="order-detail__section">
            <h2>Ürünler ({{ $order->details->count() }})</h2>
            @foreach ($order->details as $detail)
            @php
              $product = $detail->product;
              $lineTotal = (float) $detail->price * $detail->quantity;
              $existingComment = $detail->comment;
              $canReview = $order->status === \App\Enums\OrderStatus::COMPLETED && ! $existingComment;
            @endphp
            <div class="py-4 border-b-[3px] border-ink last:border-b-0 last:pb-0 first:pt-0" data-i5="order-detail-item-wrap">
            <div class="grid grid-cols-[72px_1fr_auto] gap-4 items-center" data-i5="order-detail-item">
              <a href="{{ $product ? route('shopDetail', $product->slug) : '#' }}" class="border-[3px] border-ink aspect-[3/4] overflow-hidden bg-bg block transition-shadow hover:shadow-brutal-sm [&_img]:w-full [&_img]:h-full [&_img]:object-cover" data-i5="order-detail-item__img">
                <img src="{{ $product?->images->first()?->url ?? $placeholder }}" alt="{{ $product?->title }}">
              </a>
              <div>
                <a href="{{ $product ? route('shopDetail', $product->slug) : '#' }}" class="font-heading text-card-title font-semibold leading-snug normal-case inline-block mb-1 text-ink transition-colors hover:text-accent" data-i5="order-detail-item__name">{{ $product?->title }}</a>
                <p class="text-[13px] text-muted" data-i5="order-detail-item__qty">{{ $detail->quantity }} adet × {{ number_format((float) $detail->price, 0, ',', '.') }} ₺</p>
              </div>
              <span class="font-body font-bold text-sm" data-i5="order-detail-item__price">{{ number_format($lineTotal, 0, ',', '.') }} ₺</span>
            </div>

            @if ($existingComment)
            <div class="mt-4 border-[3px] border-ink bg-bg p-4" data-i5="order-review-existing">
              <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Değerlendirmeniz</p>
              <div class="flex items-center gap-2 mb-2">
                <span class="font-body text-[15px] font-bold text-[#f59e0b]">{{ number_format((float) $existingComment->rating, 1) }} ★</span>
                @if (! $existingComment->is_visible)
                  <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2 py-0.5 border-2 border-[#d97706] text-[#92400e] bg-[#fff8e6]">Onay Bekliyor</span>
                @else
                  <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2 py-0.5 border-2 border-[#15803d] text-[#15803d] bg-[rgba(21,128,61,0.1)]">Yayında</span>
                @endif
              </div>
              <p class="text-sm text-muted leading-relaxed">{{ $existingComment->content }}</p>
            </div>
            @elseif ($canReview)
            <form action="{{ route('commentStore') }}" method="POST" class="mt-4 border-[3px] border-ink bg-bg p-4" data-i5="order-review-form">
              @csrf
              <input type="hidden" name="order_detail_id" value="{{ $detail->id }}">
              <p class="mb-3 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Bu ürünü değerlendir</p>
              <div class="mb-3">
                @include('user.partials.star-rating-input', ['name' => 'rating'])
                @error('rating') <p class="mt-1.5 text-[12px] font-semibold text-announce">{{ $message }}</p> @enderror
              </div>
              <div class="mb-3">
                <label for="comment-content-{{ $detail->id }}" class="mb-1.5 block text-[12px] font-bold uppercase tracking-[0.04em] text-muted">Yorumunuz</label>
                <textarea id="comment-content-{{ $detail->id }}" name="content" rows="3" maxlength="255" required
                          class="w-full border-[3px] border-ink bg-surface px-3 py-2.5 text-sm text-ink outline-none transition-shadow focus:shadow-brutal-sm"
                          placeholder="Ürün hakkındaki deneyiminizi paylaşın...">{{ old('content') }}</textarea>
                @error('content') <p class="mt-1.5 text-[12px] font-semibold text-announce">{{ $message }}</p> @enderror
              </div>
              <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] border-[3px] border-ink bg-accent text-on-dark shadow-brutal-sm transition-colors hover:bg-action">
                Değerlendirmeyi Gönder
              </button>
            </form>
            @endif
            </div>
            @endforeach
          </section>

          @if ($order->address)
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Teslimat Adresi</h2>
            <div class="text-sm leading-[1.7] text-muted [&_strong]:block [&_strong]:mb-1 [&_strong]:font-semibold [&_strong]:text-ink" data-i5="order-address">
              <strong>{{ $authUser->name }}</strong>
              {{ $order->address->title }} — {{ $order->address->content }}<br>
              {{ $order->address->county?->name }}, {{ $order->address->city?->name }}<br>
              @if ($authUser->phone)
              {{ $authUser->phone }}
              @endif
            </div>
          </section>
          @endif

          @if ($order->note)
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Sipariş Notu</h2>
            <p class="text-sm text-muted leading-relaxed">{{ $order->note }}</p>
          </section>
          @endif
        </div>

        <aside class="grid gap-4" data-i5="order-detail__sidebar">
          <div class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Özet</h2>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Ara Toplam</span>
              <strong>{{ number_format((float) $order->subtotal, 0, ',', '.') }} ₺</strong>
            </div>
            @if ($order->is_discount_applied)
            <div class="flex justify-between gap-3 text-sm py-2 text-accent" data-i5="order-summary__row">
              <span>İndirim @if ($order->discountLabel()) ({{ $order->discountLabel() }}) @endif</span>
              <strong>-{{ number_format((float) $order->discount_amount, 0, ',', '.') }} ₺</strong>
            </div>
            @endif
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Kargo</span>
              <strong>{{ $order->shipping_is_free ? 'Ücretsiz' : number_format((float) $order->shipping_price, 0, ',', '.').' ₺' }}</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Ödeme</span>
              <strong>Kredi / Banka Kartı</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Ödeme Durumu</span>
              <strong>{{ $order->payment?->status->label() ?? '—' }}</strong>
            </div>
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Sipariş Durumu</span>
              <strong>{{ $order->status->label() }}</strong>
            </div>
            <div class="flex justify-between gap-3 pt-4 mt-2 border-t-[3px] border-ink font-body text-lg font-bold" data-i5="order-summary__total">
              <span>Toplam</span>
              <span>{{ number_format((float) $order->total, 0, ',', '.') }} ₺</span>
            </div>
            <div class="mt-5 pt-5 border-t-[3px] border-ink text-[13px] text-muted leading-normal [&_a]:inline-block [&_a]:mt-2 [&_a]:font-bold [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px] hover:[&_a]:text-ink" data-i5="order-summary__support">
              Sorularınız mı var?
              <a href="https://wa.me/905321234567?text={{ $waText }}" target="_blank" rel="noopener noreferrer">WhatsApp ile destek al →</a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </main>
@endsection
