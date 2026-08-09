@extends('admin.layout')
@section('title', 'Sipariş ' . $order->code)
@section('page_title', 'Sipariş Detayı')
@section('breadcrumb', 'Satış / Siparişler / ' . $order->code)

@section('content')
  @if (session('success'))
    <div class="mb-5 rounded-lg border border-success/20 bg-success/10 px-4 py-3 font-body text-[13px] font-semibold text-success">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="mb-5 rounded-lg border border-danger/20 bg-danger/10 px-4 py-3 font-body text-[13px] font-semibold text-danger">{{ session('error') }}</div>
  @endif

  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.orderList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div class="min-w-0 flex-1">
      <div class="flex flex-wrap items-center gap-2">
        <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $order->code }}</h2>
        @php
          $statusClass = match ($order->status?->value) {
            'preparing' => 'bg-accent/10 text-accent',
            'shipped' => 'bg-ink/10 text-ink',
            'completed' => 'bg-success/10 text-success',
            'cancelled' => 'bg-danger/10 text-danger',
            default => 'bg-hover text-muted',
          };
        @endphp
        <span class="inline-flex rounded-md px-2.5 py-1 font-body text-[11px] font-bold {{ $statusClass }}">{{ $order->status?->label() }}</span>
      </div>
      <p class="font-body text-[13px] text-muted">{{ $order->created_at?->format('d.m.Y H:i') }} tarihinde oluşturuldu</p>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
    <div class="flex flex-col gap-6">
      {{-- Müşteri --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Müşteri Bilgileri</h3>
        </div>
        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
          <div>
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Ad Soyad</p>
            <p class="mt-1 font-body text-[14px] font-semibold text-ink">{{ $order->user?->name ?? '—' }}</p>
          </div>
          <div>
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">E-posta</p>
            <p class="mt-1 font-body text-[14px] text-ink">{{ $order->user?->email ?? '—' }}</p>
          </div>
          <div>
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Telefon</p>
            <p class="mt-1 font-body text-[14px] text-ink">{{ $order->user?->phone ?? '—' }}</p>
          </div>
          <div>
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Kullanıcı Profili</p>
            @if ($order->user)
              <a href="{{ route('admin.userDetailPage', $order->user->id) }}" class="mt-1 inline-flex font-body text-[14px] font-bold text-accent hover:underline">Profili Görüntüle</a>
            @endif
          </div>
        </div>
      </section>

      {{-- Adresler --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Teslimat & Fatura Adresi</h3>
        </div>
        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">
          <div class="rounded-lg border border-ink/10 bg-cream/40 p-4">
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Teslimat Adresi</p>
            @if ($order->address)
              <p class="mt-2 font-body text-[14px] font-bold text-ink">{{ $order->address->title }}</p>
              <p class="mt-1 font-body text-[13px] leading-relaxed text-ink">{{ $order->address->content }}</p>
              <p class="mt-2 font-body text-[12px] text-muted">{{ $order->address->formattedLocation() }} · {{ $order->address->scope_label }}</p>
            @else
              <p class="mt-2 font-body text-[13px] text-muted">Adres bulunamadı</p>
            @endif
          </div>
          <div class="rounded-lg border border-ink/10 bg-cream/40 p-4">
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Fatura Adresi</p>
            @if ($order->invoiceAddress)
              <p class="mt-2 font-body text-[14px] font-bold text-ink">{{ $order->invoiceAddress->title }}</p>
              <p class="mt-1 font-body text-[13px] leading-relaxed text-ink">{{ $order->invoiceAddress->content }}</p>
              <p class="mt-2 font-body text-[12px] text-muted">{{ $order->invoiceAddress->formattedLocation() }} · {{ $order->invoiceAddress->scope_label }}</p>
            @else
              <p class="mt-2 font-body text-[13px] text-muted">Adres bulunamadı</p>
            @endif
          </div>
        </div>
      </section>

      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Fatura Bilgileri</h3>
        </div>
        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
          <div>
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Fatura Tipi</p>
            <p class="mt-1 font-body text-[14px] font-semibold text-ink">{{ $order->invoiceTypeLabel() }}</p>
          </div>
          @if ($order->isCorporateInvoice())
            <div>
              <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Şirket Adı</p>
              <p class="mt-1 font-body text-[14px] text-ink">{{ $order->company_name }}</p>
            </div>
            <div>
              <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Vergi Numarası</p>
              <p class="mt-1 font-body text-[14px] text-ink">{{ $order->tax_number }}</p>
            </div>
          @else
            <div>
              <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">T.C. Kimlik No</p>
              <p class="mt-1 font-body text-[14px] text-ink">{{ $order->tc_no ?? '—' }}</p>
            </div>
          @endif
        </div>
      </section>

      {{-- Ürünler --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Sipariş Kalemleri</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[640px] border-collapse text-left">
            <thead>
              <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
                <th>Ürün</th>
                <th>Birim Fiyat</th>
                <th>Adet</th>
                <th class="text-right">Toplam</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
              @foreach ($order->details as $detail)
                <tr>
                  <td>
                    <div class="flex items-center gap-3">
                      <span class="h-11 w-11 shrink-0 overflow-hidden rounded-lg bg-cream">
                        @if ($detail->product?->images?->first())
                          <img src="{{ $detail->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                        @endif
                      </span>
                      <div>
                        @if ($detail->product)
                          <a href="{{ route('admin.productEditPage', $detail->product->slug) }}" class="font-body text-[14px] font-bold text-ink transition-colors hover:text-accent">
                            {{ $detail->product->title }}
                          </a>
                          <p class="font-body text-[12px] text-muted">{{ $detail->product->code }}</p>
                        @else
                          <p class="font-body text-[14px] font-bold text-ink">Ürün silinmiş</p>
                        @endif
                        @if ($detail->properties->isNotEmpty())
                          <ul class="mt-2 space-y-1">
                            @foreach ($detail->properties as $property)
                              <li class="font-body text-[12px] text-muted">
                                <span class="font-semibold text-ink/70">{{ $property->group_title }}:</span>
                                {{ $property->property_title }}
                                @if ((float) $property->price > 0)
                                  (+{{ number_format((float) $property->price, 2, ',', '.') }}₺)
                                @endif
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="font-body text-[13px] text-ink">{{ number_format((float) $detail->price, 2, ',', '.') }}₺</td>
                  <td class="font-body text-[13px] font-semibold text-ink">{{ $detail->quantity }}</td>
                  <td class="text-right font-body text-[14px] font-bold text-ink">{{ number_format((float) $detail->price * $detail->quantity, 2, ',', '.') }}₺</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>

      {{-- Sipariş dosyaları --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Yüklenen Dosyalar</h3>
        </div>
        <div class="p-5">
          @if ($order->orderFiles->isEmpty())
            <p class="font-body text-[13px] text-muted">Bu siparişe dosya yüklenmemiş.</p>
          @else
            <ul class="space-y-3">
              @foreach ($order->orderFiles as $orderFile)
                @php
                  $ext = strtolower(pathinfo($orderFile->displayName(), PATHINFO_EXTENSION));
                  $canPreview = in_array($ext, ['png', 'pdf'], true);
                @endphp
                <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-ink/10 bg-cream/40 px-4 py-3">
                  <div class="min-w-0">
                    <p class="font-body text-[14px] font-semibold text-ink break-all">{{ $orderFile->displayName() }}</p>
                    <p class="mt-0.5 font-body text-[11px] uppercase tracking-[0.06em] text-muted">{{ $ext !== '' ? '.'.$ext : 'dosya' }}</p>
                  </div>
                  <div class="flex flex-wrap gap-2">
                    @if ($canPreview)
                      <a href="{{ $orderFile->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg border border-ink/15 bg-surface px-3 py-2 font-body text-[12px] font-bold text-ink hover:bg-hover">Görüntüle</a>
                    @endif
                    <a href="{{ route('admin.orderFileDownload', ['code' => $order->code, 'fileId' => $orderFile->id]) }}" class="inline-flex items-center rounded-lg bg-accent px-3 py-2 font-body text-[12px] font-bold text-on-dark hover:bg-accent-dark">İndir</a>
                  </div>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </section>
    </div>

    <aside class="flex flex-col gap-6">
      {{-- Kargo yönetimi --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Kargo Yönetimi</h3>
        </div>
        <div class="space-y-4 p-5">
          @if ($order->isDomesticShipment())
            @php
              $carrierLabel = $order->shippingCarrierLabel() ?? 'Shipink Kargo';
            @endphp
            <div class="rounded-lg border border-accent/20 bg-accent/5 p-4">
              <p class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent">Yurt İçi · Shipink · {{ $carrierLabel }}</p>
              @if ($order->hasShipinkShipment())
                <p class="mt-2 font-body text-[13px] font-semibold text-success">Kargoya verildi</p>
                @if ($order->shipped_at)
                  <p class="mt-1 font-body text-[12px] text-muted">{{ $order->shipped_at->format('d.m.Y H:i') }} tarihinde kargoya verildi</p>
                @endif
              @else
                <p class="mt-2 font-body text-[13px] leading-relaxed text-muted">
                  Kargo oluşturulduktan sonra sipariş otomatik olarak kargoya verildi durumuna geçer.
                </p>
              @endif
            </div>

            @if ($order->tracking_number || $order->tracking_url)
              <div class="space-y-2 font-body text-[13px]">
                @if ($order->tracking_number)
                  <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Takip No</p>
                    <p class="mt-1 font-semibold text-ink">{{ $order->tracking_number }}</p>
                  </div>
                @endif
                @if ($order->tracking_url)
                  <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex font-semibold text-accent hover:underline">Kargo Takip Linki</a>
                @endif
                @if ($order->shipping_label_url)
                  <a href="{{ $order->shipping_label_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex font-semibold text-accent hover:underline">Kargo Etiketi (PDF)</a>
                @endif
                @if ($order->shipping_synced_at)
                  <p class="text-[12px] text-muted">Son sync: {{ $order->shipping_synced_at->format('d.m.Y H:i') }}</p>
                @endif
                @if ($order->isShippingSyncStale())
                  <p class="rounded-lg border border-warning/30 bg-warning/10 px-3 py-2 text-[12px] font-semibold text-warning">
                    Kargo durumu {{ config('shipink.stale_sync_hours', 6) }} saatten uzun süredir güncellenmedi. “Durumu Senkronize Et” ile kontrol edin.
                  </p>
                @endif
              </div>
            @endif

            <div class="flex flex-col gap-2">
              @if ($order->canCreateShipinkShipment() && $shipinkConfigured)
                <form action="{{ route('admin.orderShipinkCreate', $order->code) }}" method="POST" class="js-shipink-create-form space-y-3">
                  @csrf
                  @if ($packageEstimate)
                    <div class="rounded-lg border border-ink/10 bg-cream/50 p-3">
                      <p class="mb-2 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Paket Ölçüsü</p>
                      <p class="mb-3 font-body text-[12px] text-muted">
                        @if ($packageEstimate['source'] === 'calculated')
                          Sipariş ürünlerinden hesaplandı
                        @elseif ($packageEstimate['source'] === 'partial')
                          Kısmi hesap + varsayılanlar
                        @else
                          Shipink varsayılan paket
                        @endif
                        · Desi: {{ number_format((float) $packageEstimate['desi'], 2, ',', '.') }}
                      </p>
                      @if (! empty($packageEstimate['items']))
                        <ul class="mb-3 space-y-1 font-body text-[11px] text-muted">
                          @foreach ($packageEstimate['items'] as $pkgItem)
                            <li>
                              {{ $pkgItem['title'] }} × {{ $pkgItem['quantity'] }}
                              @if ($pkgItem['normalized'])
                                — {{ $pkgItem['normalized']['length'] }}×{{ $pkgItem['normalized']['width'] }}×{{ $pkgItem['normalized']['height'] }} cm
                              @else
                                — ölçü yok
                              @endif
                              @if ($pkgItem['weight'])
                                · {{ rtrim(rtrim(number_format((float) $pkgItem['weight'], 3, '.', ''), '0'), '.') }} kg
                              @endif
                            </li>
                          @endforeach
                        </ul>
                      @endif
                      @if (! empty($packageEstimate['warnings']))
                        <p class="mb-3 font-body text-[11px] text-warning">{{ implode(' ', array_slice($packageEstimate['warnings'], 0, 2)) }}</p>
                      @endif
                      <div class="grid grid-cols-2 gap-2">
                        <div>
                          <label class="mb-1 block font-body text-[11px] font-bold text-muted">Ağırlık (kg)</label>
                          <input type="number" step="0.1" min="0.1" name="package_weight" value="{{ old('package_weight', $packageEstimate['weight']) }}"
                                 class="w-full rounded-md border border-ink/10 bg-surface px-2.5 py-2 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </div>
                        <div>
                          <label class="mb-1 block font-body text-[11px] font-bold text-muted">Boy (cm)</label>
                          <input type="number" min="1" name="package_length" value="{{ old('package_length', $packageEstimate['length']) }}"
                                 class="w-full rounded-md border border-ink/10 bg-surface px-2.5 py-2 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </div>
                        <div>
                          <label class="mb-1 block font-body text-[11px] font-bold text-muted">En (cm)</label>
                          <input type="number" min="1" name="package_width" value="{{ old('package_width', $packageEstimate['width']) }}"
                                 class="w-full rounded-md border border-ink/10 bg-surface px-2.5 py-2 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </div>
                        <div>
                          <label class="mb-1 block font-body text-[11px] font-bold text-muted">Yükseklik (cm)</label>
                          <input type="number" min="1" name="package_height" value="{{ old('package_height', $packageEstimate['height']) }}"
                                 class="w-full rounded-md border border-ink/10 bg-surface px-2.5 py-2 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </div>
                      </div>
                      <p class="mt-2 font-body text-[11px] text-muted">İsterseniz değerleri güncelleyip gönderin.</p>
                    </div>
                  @endif
                  <button type="submit" class="js-shipink-create-btn inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark disabled:cursor-not-allowed disabled:opacity-60">
                    Shipink ile Kargo Oluştur
                  </button>
                </form>
              @elseif ($order->canCreateShipinkShipment() && ! $shipinkConfigured)
                <p class="rounded-lg border border-danger/20 bg-danger/10 px-3 py-2 font-body text-[12px] text-danger">
                  Shipink ayarları eksik.
                  <a href="{{ route('admin.shipinkSettings') }}" class="font-bold underline">Shipink Ayarları</a>
                  sayfasından depo ve kargo hesabını seçin.
                </p>
              @endif

              @if ($order->hasShipinkShipment())
                <form action="{{ route('admin.orderShipinkSync', $order->code) }}" method="POST">
                  @csrf
                  <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg border border-ink/15 bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
                    Durumu Senkronize Et
                  </button>
                </form>

                @if ($order->canCancelShipinkShipment())
                  <form action="{{ route('admin.orderShipinkCancel', $order->code) }}" method="POST" onsubmit="return confirm('Kargo gönderisini iptal etmek istediğinize emin misiniz?');">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg border border-danger/30 bg-danger/5 px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-danger transition-colors hover:bg-danger/10">
                      Kargoyu İptal Et
                    </button>
                  </form>
                  @if ($order->shipinkCancelDeadline())
                    <p class="font-body text-[11px] text-muted">İptal için son süre: {{ $order->shipinkCancelDeadline()->format('d.m.Y H:i') }}</p>
                  @endif
                @elseif ($order->shipment_created_at)
                  <p class="rounded-lg border border-ink/10 bg-cream/60 px-3 py-2 font-body text-[12px] text-muted">
                    Kargo iptal süresi doldu. Gönderi artık iptal edilemez.
                  </p>
                @endif
              @endif
            </div>
          @else
            <div class="rounded-lg border border-ink/10 bg-cream/40 p-4">
              <p class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Yurt Dışı · Manuel Kargo</p>
              <p class="mt-2 font-body text-[13px] leading-relaxed text-muted">
                Yurt dışı siparişlerde kargo durumu ve takip bilgileri manuel yönetilir.
              </p>
            </div>
          @endif

          @if ($order->canBeCancelledByAdmin())
            <form action="{{ route('admin.orderCancel', $order->code) }}" method="POST" onsubmit="return confirm('Bu siparişi iptal etmek istediğinize emin misiniz? Aktif kargo varsa Shipink üzerinden de iptal edilmeye çalışılır.');">
              @csrf
              <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg border border-danger/30 bg-danger/5 px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-danger transition-colors hover:bg-danger/10">
                Siparişi İptal Et
              </button>
            </form>
          @endif
        </div>
      </section>

      {{-- Durum güncelleme --}}
      <form action="{{ route('admin.orderUpdate') }}" method="POST" enctype="multipart/form-data" class="overflow-hidden rounded-xl bg-surface shadow-card">
        @csrf
        <input type="hidden" name="id" value="{{ $order->id }}">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Sipariş Yönetimi</h3>
        </div>
        <div class="space-y-4 p-5">
          <div>
            <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sipariş Durumu</label>
            @if ($order->isDomesticShipment())
              <input type="hidden" name="status" value="{{ $order->status?->value }}">
              <div class="w-full rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] font-semibold text-ink">
                {{ $order->status?->label() }}
              </div>
              <p class="mt-1.5 font-body text-[12px] text-muted">Yurt içi siparişlerde durum Shipink ile otomatik güncellenir.</p>
            @else
              <select id="status" name="status" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
                @foreach ($orderStatuses as $orderStatus)
                  <option value="{{ $orderStatus->value }}" @selected(old('status', $order->status?->value) === $orderStatus->value)>{{ $orderStatus->label() }}</option>
                @endforeach
              </select>
            @endif
            @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          @if ($order->isInternationalShipment())
          <div>
            <label for="tracking_number" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Takip Numarası</label>
            <input type="text" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent" placeholder="Opsiyonel">
            @error('tracking_number') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="tracking_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Takip Linki</label>
            <input type="url" id="tracking_url" name="tracking_url" value="{{ old('tracking_url', $order->tracking_url) }}" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent" placeholder="https://...">
            @error('tracking_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
          @endif

          <div>
            <label class="mb-2 flex cursor-pointer items-center gap-2.5">
              <input type="checkbox" name="invoice_status" value="1" @checked(old('invoice_status', $order->invoice_status)) class="h-4 w-4 rounded border-ink/20 text-accent focus:ring-accent/20">
              <span class="font-body text-[13px] font-semibold text-ink">Fatura kesildi</span>
            </label>
            <p class="font-body text-[11px] text-muted">PDF yüklemeden de işaretleyebilirsiniz. İsterseniz daha sonra fatura ekleyebilirsiniz.</p>
          </div>

          <div>
            <label for="invoice_pdf" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Fatura PDF <span class="font-medium text-muted">(opsiyonel)</span></label>
            <input type="file" id="invoice_pdf" name="invoice_pdf" accept=".pdf,application/pdf"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[13px] text-ink file:mr-3 file:rounded-md file:border-0 file:bg-accent file:px-3 file:py-1.5 file:text-[11px] file:font-bold file:uppercase file:text-on-dark">
            <p class="mt-1.5 font-body text-[11px] text-muted">Yalnızca PDF, en fazla 20MB. Yüklenirse müşteri sipariş detayında görür.</p>
            @error('invoice_pdf') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror

            @if ($order->invoiceFile)
              <div class="mt-3 rounded-lg border border-ink/10 bg-cream/50 px-3 py-3">
                <p class="font-body text-[12px] font-semibold text-ink break-all">{{ $order->invoiceFile->displayName() }}</p>
                <div class="mt-2 flex flex-wrap gap-2">
                  <a href="{{ $order->invoiceFile->url }}" target="_blank" rel="noopener noreferrer"
                     class="inline-flex items-center rounded-lg border border-ink/15 bg-surface px-3 py-1.5 font-body text-[11px] font-bold text-ink hover:bg-hover">Görüntüle</a>
                  <a href="{{ route('admin.orderFileDownload', ['code' => $order->code, 'fileId' => $order->invoiceFile->id]) }}"
                     class="inline-flex items-center rounded-lg bg-accent px-3 py-1.5 font-body text-[11px] font-bold text-on-dark hover:bg-accent-dark">İndir</a>
                  <button type="submit" form="order-invoice-delete-form"
                          class="inline-flex items-center rounded-lg border border-danger/30 bg-danger/5 px-3 py-1.5 font-body text-[11px] font-bold text-danger hover:bg-danger/10"
                          onclick="return confirm('Yüklü fatura PDF silinsin mi?');">Sil</button>
                </div>
              </div>
            @endif
          </div>

          <div>
            <label for="note" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Admin Notu</label>
            <textarea id="note" name="note" rows="3" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15" placeholder="İç not...">{{ old('note', $order->note) }}</textarea>
            @error('note') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
            Güncelle
          </button>
        </div>
      </form>

      @if ($order->invoiceFile)
        <form id="order-invoice-delete-form" action="{{ route('admin.orderInvoiceDelete', $order->code) }}" method="POST" class="hidden">
          @csrf
        </form>
      @endif

      {{-- Özet --}}
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Sipariş Özeti</h3>
        </div>
        <div class="space-y-3 p-5 font-body text-[14px]">
          <div class="flex justify-between text-ink">
            <span class="text-muted">Ara Toplam</span>
            <span class="font-semibold">{{ number_format((float) ($order->subtotal ?? 0), 2, ',', '.') }}₺</span>
          </div>
          @if ($order->is_discount_applied)
            <div class="flex justify-between text-danger">
              <span>İndirim @if ($order->discountLabel()) ({{ $order->discountLabel() }}) @endif</span>
              <span class="font-semibold">-{{ number_format((float) $order->discount_amount, 2, ',', '.') }}₺</span>
            </div>
          @endif
          <div class="flex justify-between text-ink">
            <span class="text-muted">Kargo</span>
            <span class="font-semibold">
              @if ($order->shipping_is_free)
                Ücretsiz
              @else
                {{ number_format((float) ($order->shipping_price ?? 0), 2, ',', '.') }}₺
              @endif
            </span>
          </div>
          <div class="flex justify-between border-t border-ink/10 pt-3 text-[16px] font-bold text-ink">
            <span>Genel Toplam</span>
            <span>{{ number_format((float) $order->total, 2, ',', '.') }}₺</span>
          </div>
        </div>
      </section>
    </aside>
  </div>
@endsection

@section('scripts')
<script>
(() => {
  document.querySelectorAll('.js-shipink-create-form').forEach((form) => {
    form.addEventListener('submit', () => {
      const button = form.querySelector('.js-shipink-create-btn');
      if (button) {
        button.disabled = true;
        button.textContent = 'Oluşturuluyor...';
      }
    });
  });
})();
</script>
@endsection
