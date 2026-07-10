@extends('admin.layout')
@section('title', 'Sipariş ' . $order->code)
@section('page_title', 'Sipariş Detayı')
@section('breadcrumb', 'Satış / Siparişler / ' . $order->code)

@section('content')
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
              <p class="mt-2 font-body text-[12px] text-muted">{{ $order->address->county?->name }} / {{ $order->address->city?->name }}</p>
            @else
              <p class="mt-2 font-body text-[13px] text-muted">Adres bulunamadı</p>
            @endif
          </div>
          <div class="rounded-lg border border-ink/10 bg-cream/40 p-4">
            <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Fatura Adresi</p>
            @if ($order->invoiceAddress)
              <p class="mt-2 font-body text-[14px] font-bold text-ink">{{ $order->invoiceAddress->title }}</p>
              <p class="mt-1 font-body text-[13px] leading-relaxed text-ink">{{ $order->invoiceAddress->content }}</p>
              <p class="mt-2 font-body text-[12px] text-muted">{{ $order->invoiceAddress->county?->name }} / {{ $order->invoiceAddress->city?->name }}</p>
            @else
              <p class="mt-2 font-body text-[13px] text-muted">Adres bulunamadı</p>
            @endif
          </div>
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
    </div>

    <aside class="flex flex-col gap-6">
      {{-- Durum güncelleme --}}
      <form action="{{ route('admin.orderUpdate') }}" method="POST" class="overflow-hidden rounded-xl bg-surface shadow-card">
        @csrf
        <input type="hidden" name="id" value="{{ $order->id }}">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Sipariş Yönetimi</h3>
        </div>
        <div class="space-y-4 p-5">
          <div>
            <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sipariş Durumu</label>
            <select id="status" name="status" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
              @foreach ($orderStatuses as $orderStatus)
                <option value="{{ $orderStatus->value }}" @selected(old('status', $order->status?->value) === $orderStatus->value)>{{ $orderStatus->label() }}</option>
              @endforeach
            </select>
            @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="mb-2 flex cursor-pointer items-center gap-2.5">
              <input type="checkbox" name="invoice_status" value="1" @checked(old('invoice_status', $order->invoice_status)) class="h-4 w-4 rounded border-ink/20 text-accent focus:ring-accent/20">
              <span class="font-body text-[13px] font-semibold text-ink">Fatura kesildi</span>
            </label>
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
              <span>İndirim (%{{ $order->discount_slice }})</span>
              <span class="font-semibold">Uygulandı</span>
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
