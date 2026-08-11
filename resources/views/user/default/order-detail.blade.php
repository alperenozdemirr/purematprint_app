@extends('user.layout')
@section('title','Sipariş Detay')
@section('content')
@php
  $authUser = auth()->user();
  $placeholder = asset('user/assets/foto5.jpeg');
  $totalQty = $order->details->sum('quantity');
  $statusClass = match ($order->status) {
      \App\Enums\OrderStatus::PENDING_PAYMENT => 'is-pending',
      \App\Enums\OrderStatus::PREPARING => 'is-processing',
      \App\Enums\OrderStatus::SHIPPED => 'is-shipped',
      \App\Enums\OrderStatus::COMPLETED => 'is-delivered',
      \App\Enums\OrderStatus::CANCELLED => 'is-cancelled',
  };
  $steps = [
      ['label' => 'Sipariş Alındı', 'done' => $order->status !== \App\Enums\OrderStatus::PENDING_PAYMENT],
      ['label' => 'Hazırlanıyor', 'done' => in_array($order->status, [\App\Enums\OrderStatus::PREPARING, \App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::COMPLETED], true)],
      ['label' => 'Kargoya Verildi', 'done' => in_array($order->status, [\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::COMPLETED], true)],
      ['label' => 'Tamamlandı', 'done' => $order->status === \App\Enums\OrderStatus::COMPLETED],
  ];
  $currentStep = match ($order->status) {
      \App\Enums\OrderStatus::PENDING_PAYMENT => 0,
      \App\Enums\OrderStatus::PREPARING => 1,
      \App\Enums\OrderStatus::SHIPPED => 2,
      \App\Enums\OrderStatus::COMPLETED => 3,
      default => 0,
  };
  $waLink = $siteSetting->whatsappLink('Merhaba, '.$order->code.' numaralı siparişim hakkında bilgi almak istiyorum.');
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
          <p class="text-[13px] text-muted mt-1.5" data-i5="order-detail__meta">{{ $order->created_at->translatedFormat('j F Y H:i') }} · {{ $totalQty }} ürün · {{ $order->payment?->provider?->label() ?? 'iyzico' }}</p>
        </div>
        <span class="font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2.5 py-[5px] border-2 border-ink [&.is-pending]:bg-[#fff8e6] [&.is-pending]:border-[#d97706] [&.is-pending]:text-[#92400e] [&.is-processing]:bg-accent/10 [&.is-processing]:border-accent [&.is-processing]:text-accent [&.is-shipped]:bg-accent/15 [&.is-shipped]:border-accent [&.is-shipped]:text-accent-dark [&.is-delivered]:bg-[rgba(21,128,61,0.1)] [&.is-delivered]:border-[#15803d] [&.is-delivered]:text-[#15803d] [&.is-cancelled]:bg-[rgba(182,29,15,0.08)] [&.is-cancelled]:border-announce [&.is-cancelled]:text-announce {{ $statusClass }}" data-i5="order-card__status">{{ $order->status->label() }}</span>
      </div>

      <div class="flex flex-wrap items-center gap-2.5 mb-7 pb-6 border-b-[3px] border-ink" data-i5="order-detail__actions">
        <a data-i5="btn--outline" href="{{ route('orderReorder', $order->code) }}"
           onclick="event.preventDefault(); document.getElementById('order-reorder-form').submit();"
           class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">Siparişi Tekrarla</a>
        <form id="order-reorder-form" action="{{ route('orderReorder', $order->code) }}" method="POST" class="hidden">@csrf</form>
        @if ($waLink)
        <a data-i5="btn--outline" href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">WhatsApp Destek</a>
        @endif
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
                @if ($detail->properties->isNotEmpty())
                  <ul class="mb-1.5 space-y-0.5 text-[12px] text-muted">
                    @foreach ($detail->properties as $property)
                      <li>
                        <span class="font-semibold text-ink/70">{{ $property->group_title }}:</span>
                        {{ $property->property_title }}
                      </li>
                    @endforeach
                  </ul>
                @endif
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
              @if ($existingComment->images->isNotEmpty())
                <div class="mt-3 flex flex-wrap gap-2">
                  @foreach ($existingComment->images as $commentImage)
                    <a href="{{ $commentImage->url }}" target="_blank" rel="noopener noreferrer" class="block h-16 w-16 overflow-hidden border-2 border-ink bg-surface">
                      <img src="{{ $commentImage->url }}" alt="" class="h-full w-full object-cover">
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
            @elseif ($canReview)
            <form action="{{ route('commentStore') }}" method="POST" enctype="multipart/form-data" class="mt-4 border-[3px] border-ink bg-bg p-4" data-i5="order-review-form">
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
              <div class="mb-3">
                <label for="comment-images-{{ $detail->id }}" class="mb-1.5 block text-[12px] font-bold uppercase tracking-[0.04em] text-muted">Görseller (opsiyonel)</label>
                <input type="file" id="comment-images-{{ $detail->id }}" name="images[]" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}" multiple
                       class="w-full border-[3px] border-ink bg-surface px-3 py-2.5 text-sm text-ink file:mr-3 file:border-0 file:bg-accent file:px-3 file:py-1.5 file:text-[11px] file:font-bold file:uppercase file:text-on-dark">
                <p class="mt-1.5 text-[11px] text-muted">En fazla 4 görsel ({{ \App\Support\ImageUploadRules::humanList() }}), her biri max 8MB</p>
                @error('images') <p class="mt-1.5 text-[12px] font-semibold text-announce">{{ $message }}</p> @enderror
                @error('images.*') <p class="mt-1.5 text-[12px] font-semibold text-announce">{{ $message }}</p> @enderror
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
              {{ $order->address->formattedLocation() }}<br>
              @if ($authUser->phone)
              {{ $authUser->phone }}
              @endif
            </div>
          </section>
          @endif

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Fatura Bilgileri</h2>
            <div class="text-sm leading-[1.7] text-muted space-y-1">
              <p><span class="font-semibold text-ink">Tip:</span> {{ $order->invoiceTypeLabel() }}</p>
              @if ($order->isCorporateInvoice())
                <p><span class="font-semibold text-ink">Şirket:</span> {{ $order->company_name }}</p>
                <p><span class="font-semibold text-ink">Vergi No:</span> {{ $order->tax_number }}</p>
              @else
                <p><span class="font-semibold text-ink">T.C. Kimlik No:</span> {{ $order->tc_no }}</p>
              @endif
              <p>
                <span class="font-semibold text-ink">Durum:</span>
                {{ $order->invoice_status ? 'Fatura kesildi' : 'Henüz kesilmedi' }}
              </p>
            </div>

            @if ($order->invoiceFile)
              <div class="mt-5 border-[2px] border-ink bg-bg px-4 py-3">
                <p class="mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-muted">Fatura PDF</p>
                <p class="mb-3 text-sm font-semibold text-ink break-all">{{ $order->invoiceFile->displayName() }}</p>
                <div class="flex flex-wrap gap-2">
                  <a href="{{ $order->invoiceFile->url }}" target="_blank" rel="noopener noreferrer"
                     class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-surface hover:bg-hover">Görüntüle</a>
                  <a href="{{ route('orderFileDownload', ['code' => $order->code, 'fileId' => $order->invoiceFile->id]) }}"
                     class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-action text-on-dark hover:bg-action-hover">İndir</a>
                </div>
              </div>
            @endif
          </section>

          @if ($order->note)
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Sipariş Notu</h2>
            <p class="text-sm text-muted leading-relaxed">{{ $order->note }}</p>
          </section>
          @endif

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Yüklenen Dosyalar</h2>
            @if ($order->orderFiles->isEmpty())
              <p class="text-sm text-muted mb-4">Bu siparişe henüz dosya yüklenmemiş.</p>
            @else
              <ul class="grid gap-3 mb-4">
                @foreach ($order->orderFiles as $orderFile)
                  @php
                    $ext = strtolower(pathinfo($orderFile->displayName(), PATHINFO_EXTENSION));
                    $canPreview = in_array($ext, ['png', 'pdf', 'jpg', 'jpeg'], true);
                  @endphp
                  <li class="flex flex-wrap items-center justify-between gap-3 border-[2px] border-ink bg-bg px-4 py-3">
                    <span class="text-sm font-semibold text-ink break-all">{{ $orderFile->displayName() }}</span>
                    <span class="flex flex-wrap gap-2">
                      @if ($canPreview)
                        <a href="{{ $orderFile->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-surface hover:bg-hover">Görüntüle</a>
                      @endif
                      <a href="{{ route('orderFileDownload', ['code' => $order->code, 'fileId' => $orderFile->id]) }}" class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-action text-on-dark hover:bg-action-hover">İndir</a>
                      @if ($canManagePreparing ?? false)
                        <form action="{{ route('orderCustomerFileDelete', ['code' => $order->code, 'fileId' => $orderFile->id]) }}" method="POST" onsubmit="return confirm('Bu dosyayı silmek istediğinize emin misiniz?')">
                          @csrf
                          <button type="submit" class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-surface text-danger hover:bg-hover">Sil</button>
                        </form>
                      @endif
                    </span>
                  </li>
                @endforeach
              </ul>
            @endif

            @if ($canManagePreparing ?? false)
              <form action="{{ route('orderCustomerFileUpload', $order->code) }}" method="POST" enctype="multipart/form-data" class="grid gap-3 border-t-[3px] border-ink pt-4">
                @csrf
                <p class="text-sm text-muted m-0">Sipariş hazırlanırken dosyanızı silebilir veya yeniden yükleyebilirsiniz. Yeni yükleme mevcut dosyanın yerine geçer.</p>
                <label class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-ink" for="customer-order-file">Dosya yükle / değiştir</label>
                <input id="customer-order-file" type="file" name="file" required accept=".png,.pdf,.psd,.jpg,.jpeg"
                       class="w-full border-[2px] border-ink bg-bg px-3 py-2 font-body text-[13px]">
                @error('file') <p class="text-sm text-danger m-0">{{ $message }}</p> @enderror
                <button type="submit" class="inline-flex w-fit items-center px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-action text-on-dark hover:bg-action-hover">Dosyayı Gönder</button>
              </form>
            @endif
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface p-6 [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_h2]:mb-5 [&_h2]:pb-3 [&_h2]:border-b-[3px] [&_h2]:border-ink" data-i5="order-detail__section">
            <h2>Tasarım Onayı</h2>
            <p class="text-sm text-muted mb-4">
              Durum:
              <strong class="text-ink">{{ $order->design_status?->label() ?? 'Tasarım Yok' }}</strong>
            </p>

            @if ($order->designFile)
              @php
                $designExt = strtolower(pathinfo($order->designFile->displayName(), PATHINFO_EXTENSION));
                $designPreview = in_array($designExt, ['png', 'pdf', 'jpg', 'jpeg'], true);
              @endphp
              <div class="flex flex-wrap items-center justify-between gap-3 border-[2px] border-ink bg-bg px-4 py-3 mb-4">
                <span class="text-sm font-semibold text-ink break-all">{{ $order->designFile->displayName() }}</span>
                <span class="flex flex-wrap gap-2">
                  @if ($designPreview)
                    <a href="{{ $order->designFile->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-surface hover:bg-hover">Görüntüle</a>
                  @endif
                  <a href="{{ route('orderFileDownload', ['code' => $order->code, 'fileId' => $order->designFile->id]) }}" class="inline-flex items-center px-3 py-1.5 font-body text-[11px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-action text-on-dark hover:bg-action-hover">İndir</a>
                </span>
              </div>
            @else
              <p class="text-sm text-muted mb-4">Henüz yönetici tarafından tasarım yüklenmedi.</p>
            @endif

            @if (($canManagePreparing ?? false) && $order->designFile && $order->design_status !== \App\Enums\OrderDesignStatus::APPROVED && $order->design_status !== \App\Enums\OrderDesignStatus::NONE)
              <form action="{{ route('orderDesignDecide', $order->code) }}" method="POST" class="grid gap-3 border-t-[3px] border-ink pt-4">
                @csrf
                <label class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-ink" for="design-note">Not (revize için zorunlu)</label>
                <textarea id="design-note" name="note" rows="3" maxlength="2000" placeholder="Revize isteğinizi veya onay notunuzu yazın..."
                          class="w-full border-[2px] border-ink bg-bg px-3 py-2 font-body text-[13px]">{{ old('note') }}</textarea>
                @error('note') <p class="text-sm text-danger m-0">{{ $message }}</p> @enderror
                @error('design') <p class="text-sm text-danger m-0">{{ $message }}</p> @enderror
                <div class="flex flex-wrap gap-2">
                  <button type="submit" name="decision" value="approve" class="inline-flex items-center px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-action text-on-dark hover:bg-action-hover">Onayla</button>
                  <button type="submit" name="decision" value="revise" class="inline-flex items-center px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] border-[2px] border-ink bg-surface text-ink hover:bg-hover">Revize İste</button>
                </div>
              </form>
            @elseif (!($canManagePreparing ?? false))
              <p class="text-sm text-muted m-0">Tasarım işlemleri yalnızca sipariş “Hazırlanıyor” durumundayken yapılabilir.</p>
            @endif

            @if ($order->designRequests->isNotEmpty())
              <div class="mt-5 border-t-[3px] border-ink pt-4">
                <h3 class="font-body text-[12px] font-bold uppercase tracking-[0.06em] mb-3">Talepler / Geçmiş</h3>
                <ul class="grid gap-3">
                  @foreach ($order->designRequests as $requestItem)
                    <li class="border-[2px] border-ink bg-bg px-4 py-3">
                      <p class="text-sm font-semibold text-ink m-0">{{ $requestItem->type->label() }} · {{ $requestItem->actor_type->label() }}</p>
                      <p class="text-xs text-muted mt-1 mb-0">{{ $requestItem->created_at?->format('d.m.Y H:i') }}</p>
                      @if (filled($requestItem->note))
                        <p class="text-sm text-muted mt-2 mb-0">{{ $requestItem->note }}</p>
                      @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </section>
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
            @if ($order->design_type)
            <div class="flex justify-between gap-3 text-sm py-2 text-muted" data-i5="order-summary__row">
              <span>Tasarım</span>
              <strong class="text-right">{{ $order->design_type->label() }}</strong>
            </div>
            @endif
            @if ($order->tracking_url || $order->hasShipinkShipment())
            <div class="mt-4 p-4 border-[3px] border-ink bg-bg text-sm" data-i5="order-tracking">
              <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-2">Kargo Takibi</p>
              @if ($order->shippingCarrierLabel())
              <p class="text-muted mb-2">Kargo Firması: <strong class="text-ink">{{ $order->shippingCarrierLabel() }}</strong></p>
              @endif
              @if ($order->tracking_number)
              <p class="text-muted mb-2">Takip No: <strong class="text-ink">{{ $order->tracking_number }}</strong></p>
              @endif
              @if ($order->tracking_url)
              <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-accent underline underline-offset-[3px] hover:text-ink">Kargonu Takip Et →</a>
              @endif
            </div>
            @endif
            <div class="flex justify-between gap-3 pt-4 mt-2 border-t-[3px] border-ink font-body text-lg font-bold" data-i5="order-summary__total">
              <span>Toplam</span>
              <span>{{ number_format((float) $order->total, 0, ',', '.') }} ₺</span>
            </div>
            <div class="mt-5 pt-5 border-t-[3px] border-ink text-[13px] text-muted leading-normal [&_a]:inline-block [&_a]:mt-2 [&_a]:font-bold [&_a]:text-accent [&_a]:underline [&_a]:underline-offset-[3px] hover:[&_a]:text-ink" data-i5="order-summary__support">
              Sorularınız mı var?
              @if ($waLink)
              <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer">WhatsApp ile destek al →</a>
              @endif
            </div>
          </div>
        </aside>
      </div>
    </div>
  </main>
@endsection
