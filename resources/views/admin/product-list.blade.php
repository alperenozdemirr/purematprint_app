@extends('admin.layout')
@section('title', 'Ürünler')
@section('page_title', 'Ürünler')
@section('breadcrumb', 'Katalog / Ürünler')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Ürün Listesi</h2>
      <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $products->total() }}</span> ürün</p>
    </div>
    <a href="{{ route('admin.productStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Ürün
    </a>
  </div>

  <form action="{{ route('admin.productList') }}" method="get" class="mb-6 rounded-xl bg-surface p-4 shadow-card">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_auto_auto_auto]">
      <div class="flex items-center overflow-hidden rounded-lg border border-ink/10 bg-cream">
        <span class="flex w-11 shrink-0 items-center justify-center text-muted">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="7.8"/><path stroke-linecap="square" d="m17 17 4.5 4.5"/></svg>
        </span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Ürün adı veya kodu ara..." class="w-full border-0 bg-transparent px-3 py-2.5 font-body text-[14px] text-ink outline-none placeholder:text-muted">
      </div>
      <select name="category" class="rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
        <option value="">Tüm Kategoriler</option>
        @foreach ($categoryOptions as $categoryOption)
          <option value="{{ $categoryOption['id'] }}" @selected(request('category') == $categoryOption['id'])>{{ $categoryOption['label'] }}</option>
        @endforeach
      </select>
      <select name="status" class="rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] font-medium text-ink outline-none focus:border-accent">
        <option value="">Tüm Durumlar</option>
        <option value="active" @selected(request('status') === 'active')>Aktif</option>
        <option value="passive" @selected(request('status') === 'passive')>Pasif</option>
      </select>
      <button type="submit" class="rounded-lg bg-ink px-5 py-2.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-action">Filtrele</button>
    </div>
  </form>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[860px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Ürün</th>
            <th>Kod</th>
            <th>Kategori</th>
            <th>Fiyat</th>
            <th>Stok</th>
            <th>Durum</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($products as $product)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <div class="flex items-center gap-3">
                  <span class="h-11 w-11 shrink-0 overflow-hidden rounded-lg bg-cream">
                    @if ($product->images->first())
                      <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="h-full w-full object-cover">
                    @else
                      <span class="flex h-full w-full items-center justify-center text-muted">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2l1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                      </span>
                    @endif
                  </span>
                  <div class="min-w-0">
                    <p class="truncate font-body text-[14px] font-bold text-ink">{{ $product->title }}</p>
                    <div class="mt-0.5 flex flex-wrap gap-1">
                      @if ($product->featured_status)
                        <span class="rounded bg-accent/10 px-1.5 py-px font-body text-[10px] font-bold uppercase tracking-wide text-accent">Öne çıkan</span>
                      @endif
                      @if ($product->introduction_status)
                        <span class="rounded bg-badge-sale/10 px-1.5 py-px font-body text-[10px] font-bold uppercase tracking-wide text-badge-sale">Tanıtım</span>
                      @endif
                    </div>
                  </div>
                </div>
              </td>
              <td class="font-body text-[13px] font-medium text-muted">{{ $product->code }}</td>
              <td class="font-body text-[13px] text-ink">{{ $product->category?->name ?? '—' }}</td>
              <td class="font-body text-[13px] font-bold text-ink">{{ number_format((float) $product->price, 0, ',', '.') }}₺</td>
              <td>
                @if ($product->stock_count == 0)
                  <span class="inline-flex rounded-md bg-danger/10 px-2 py-0.5 font-body text-[12px] font-bold text-danger">Tükendi</span>
                @elseif ($product->stock_count <= 5)
                  <span class="inline-flex rounded-md bg-warning/10 px-2 py-0.5 font-body text-[12px] font-bold text-warning">{{ $product->stock_count }} adet</span>
                @else
                  <span class="font-body text-[13px] font-semibold text-ink">{{ $product->stock_count }} adet</span>
                @endif
              </td>
              <td>
                @if ($product->status?->value === 'active')
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-success/10 px-2.5 py-1 font-body text-[11px] font-bold text-success">
                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span>Aktif
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-hover px-2.5 py-1 font-body text-[11px] font-bold text-muted">
                    <span class="h-1.5 w-1.5 rounded-full bg-muted"></span>Pasif
                  </span>
                @endif
              </td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.productEditPage', $product->slug) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                  </a>
                  <a href="{{ route('admin.productDelete', $product->id) }}" aria-label="Sil" onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz ürün bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($products->hasPages())
      <div class="flex flex-col items-center justify-between gap-3 border-t border-ink/10 px-4 py-3.5 sm:flex-row">
        <p class="font-body text-[13px] text-muted">{{ $products->firstItem() }}–{{ $products->lastItem() }} / {{ $products->total() }} gösteriliyor</p>
        <div class="[&_.pagination]:flex [&_.pagination]:gap-1">
          {{ $products->links() }}
        </div>
      </div>
    @endif
  </div>
@endsection
