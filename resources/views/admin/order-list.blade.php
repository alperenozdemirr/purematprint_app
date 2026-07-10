@extends('admin.layout')
@section('title', 'Siparişler')
@section('page_title', 'Siparişler')
@section('breadcrumb', 'Satış / Siparişler')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Sipariş Listesi</h2>
    <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $orders->total() }}</span> sipariş</p>
  </div>

  <form action="{{ route('admin.orderList') }}" method="get" class="mb-6 rounded-xl bg-surface p-4 shadow-card">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_auto_auto]">
      <div class="flex items-center overflow-hidden rounded-lg border border-ink/10 bg-cream">
        <span class="flex w-11 shrink-0 items-center justify-center text-muted">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="7.8"/><path stroke-linecap="square" d="m17 17 4.5 4.5"/></svg>
        </span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Sipariş kodu, müşteri adı, e-posta veya telefon..." class="w-full border-0 bg-transparent px-3 py-2.5 font-body text-[14px] text-ink outline-none placeholder:text-muted">
      </div>
      <select name="status" class="rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
        <option value="">Tüm Durumlar</option>
        @foreach ($orderStatuses as $orderStatus)
          <option value="{{ $orderStatus->value }}" @selected(request('status') === $orderStatus->value)>{{ $orderStatus->label() }}</option>
        @endforeach
      </select>
      <button type="submit" class="rounded-lg bg-ink px-5 py-2.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-action">Filtrele</button>
    </div>
  </form>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[960px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Sipariş</th>
            <th>Müşteri</th>
            <th>Tarih</th>
            <th>Tutar</th>
            <th>Durum</th>
            <th class="text-right">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($orders as $order)
            @php
              $statusClass = match ($order->status?->value) {
                'preparing' => 'bg-accent/10 text-accent',
                'shipped' => 'bg-ink/10 text-ink',
                'completed' => 'bg-success/10 text-success',
                'cancelled' => 'bg-danger/10 text-danger',
                default => 'bg-hover text-muted',
              };
            @endphp
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $order->code }}</p>
                @if ($order->invoice_status)
                  <span class="mt-0.5 inline-flex rounded bg-cream px-1.5 py-px font-body text-[10px] font-bold uppercase text-muted">Fatura kesildi</span>
                @endif
              </td>
              <td>
                <p class="font-body text-[14px] font-semibold text-ink">{{ $order->user?->name ?? '—' }}</p>
                <p class="font-body text-[12px] text-muted">{{ $order->user?->email }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $order->created_at?->format('d.m.Y H:i') }}</td>
              <td class="font-body text-[14px] font-bold text-ink">{{ number_format((float) $order->total, 2, ',', '.') }}₺</td>
              <td>
                <span class="inline-flex rounded-md px-2.5 py-1 font-body text-[11px] font-bold {{ $statusClass }}">
                  {{ $order->status?->label() }}
                </span>
              </td>
              <td>
                <div class="flex justify-end">
                  <a href="{{ route('admin.orderDetailPage', $order->code) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-cream px-3 py-2 font-body text-[12px] font-bold text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    Detay
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz sipariş bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($orders->hasPages())
      <div class="flex flex-col items-center justify-between gap-3 border-t border-ink/10 px-4 py-3.5 sm:flex-row">
        <p class="font-body text-[13px] text-muted">{{ $orders->firstItem() }}–{{ $orders->lastItem() }} / {{ $orders->total() }} gösteriliyor</p>
        <div>{{ $orders->links() }}</div>
      </div>
    @endif
  </div>
@endsection
