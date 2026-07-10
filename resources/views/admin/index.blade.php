@extends('admin.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Genel bakış')

@section('content')
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl bg-surface p-5 shadow-card transition-shadow hover:shadow-card-md">
      <div class="flex items-start justify-between">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-accent text-on-dark">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
        </span>
      </div>
      <p class="mt-4 font-heading text-[32px] font-bold leading-none text-ink">{{ $totalOrders }}</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Toplam Sipariş</p>
    </div>

    <div class="rounded-xl bg-surface p-5 shadow-card transition-shadow hover:shadow-card-md">
      <div class="flex items-start justify-between">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-ink text-on-dark">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </span>
      </div>
      <p class="mt-4 font-heading text-[32px] font-bold leading-none text-ink">{{ number_format((float) $monthlyRevenue, 0, ',', '.') }}₺</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Bu Ay Ciro</p>
    </div>

    <div class="rounded-xl bg-surface p-5 shadow-card transition-shadow hover:shadow-card-md">
      <div class="flex items-start justify-between">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-action text-on-dark">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7 12 3 4 7v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 11v10"/></svg>
        </span>
        <span class="inline-flex items-center gap-1 rounded-md bg-hover px-2 py-0.5 font-body text-[11px] font-bold text-muted">{{ $activeProducts }} aktif</span>
      </div>
      <p class="mt-4 font-heading text-[32px] font-bold leading-none text-ink">{{ $totalProducts }}</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Toplam Ürün</p>
    </div>

    <div class="rounded-xl bg-surface p-5 shadow-card transition-shadow hover:shadow-card-md">
      <div class="flex items-start justify-between">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-badge-sale text-on-dark">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>
        </span>
      </div>
      <p class="mt-4 font-heading text-[32px] font-bold leading-none text-ink">{{ $totalUsers }}</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Kayıtlı Müşteri</p>
    </div>
  </div>

  <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <section class="overflow-hidden rounded-xl bg-surface shadow-card xl:col-span-2">
      <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h2 class="font-heading text-[17px] font-bold text-ink">Son Siparişler</h2>
        <a href="{{ route('admin.orderList') }}" class="font-body text-[12px] font-bold uppercase tracking-[0.06em] text-accent hover:text-accent-dark">Tümü →</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] border-collapse text-left">
          <thead>
            <tr class="bg-cream/60 [&_th]:px-5 [&_th]:py-3 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
              <th>Sipariş</th>
              <th>Müşteri</th>
              <th>Tutar</th>
              <th>Durum</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/8 [&_td]:px-5 [&_td]:py-3.5 [&_td]:align-middle">
            @forelse ($recentOrders as $order)
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
                  <a href="{{ route('admin.orderDetailPage', $order->code) }}" class="font-body text-[13px] font-bold text-ink hover:text-accent">{{ $order->code }}</a>
                </td>
                <td class="font-body text-[13px] text-ink">{{ $order->user?->name ?? '—' }}</td>
                <td class="font-body text-[13px] font-semibold text-ink">{{ number_format((float) $order->total, 0, ',', '.') }}₺</td>
                <td><span class="inline-flex rounded-md px-2.5 py-1 font-body text-[11px] font-bold {{ $statusClass }}">{{ $order->status?->label() }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center font-body text-[14px] text-muted">Henüz sipariş bulunmuyor.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    <div class="flex flex-col gap-6">
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h2 class="font-heading text-[17px] font-bold text-ink">Hızlı İşlemler</h2>
        </div>
        <div class="grid grid-cols-2 gap-3 p-5">
          <a href="{{ route('admin.productStorePage') }}" class="flex flex-col items-center gap-2 rounded-xl bg-accent px-3 py-4 text-center text-on-dark transition-colors hover:bg-accent-dark">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            <span class="font-body text-[12px] font-bold uppercase tracking-[0.04em]">Ürün Ekle</span>
          </a>
          <a href="{{ route('admin.productList') }}" class="flex flex-col items-center gap-2 rounded-xl bg-cream px-3 py-4 text-center text-ink transition-colors hover:bg-hover">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
            <span class="font-body text-[12px] font-bold uppercase tracking-[0.04em]">Ürünler</span>
          </a>
          <a href="{{ route('admin.orderList') }}" class="flex flex-col items-center gap-2 rounded-xl bg-cream px-3 py-4 text-center text-ink transition-colors hover:bg-hover">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18"/></svg>
            <span class="font-body text-[12px] font-bold uppercase tracking-[0.04em]">Siparişler</span>
          </a>
          <a href="{{ route('admin.userList') }}" class="flex flex-col items-center gap-2 rounded-xl bg-cream px-3 py-4 text-center text-ink transition-colors hover:bg-hover">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>
            <span class="font-body text-[12px] font-bold uppercase tracking-[0.04em]">Kullanıcılar</span>
          </a>
        </div>
      </section>

      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
          <h2 class="font-heading text-[17px] font-bold text-ink">Stok Azalanlar</h2>
          <span class="rounded-md bg-danger/10 px-2 py-0.5 font-body text-[11px] font-bold text-danger">{{ $lowStockProducts->count() }}</span>
        </div>
        <ul class="divide-y divide-ink/8">
          @forelse ($lowStockProducts as $product)
            <li class="flex items-center justify-between px-5 py-3">
              <a href="{{ route('admin.productEditPage', $product->slug) }}" class="font-body text-[13px] font-medium text-ink hover:text-accent">{{ $product->title }}</a>
              <span class="font-body text-[13px] font-bold {{ $product->stock_count == 0 ? 'text-danger' : 'text-warning' }}">{{ $product->stock_count }} adet</span>
            </li>
          @empty
            <li class="px-5 py-6 text-center font-body text-[14px] text-muted">Stok azalan ürün yok.</li>
          @endforelse
        </ul>
      </section>
    </div>
  </div>
@endsection
