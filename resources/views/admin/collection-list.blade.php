@extends('admin.layout')
@section('title', 'Koleksiyonlar')
@section('page_title', 'Koleksiyonlar')
@section('breadcrumb', 'Katalog / Koleksiyonlar')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Koleksiyon Listesi</h2>
      <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $collections->total() }}</span> koleksiyon</p>
    </div>
    <a href="{{ route('admin.collectionStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Koleksiyon
    </a>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[960px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Görsel</th>
            <th>Başlık</th>
            <th>Ürün</th>
            <th>Sıra</th>
            <th>Durum</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($collections as $collection)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <div class="h-14 w-24 overflow-hidden rounded-lg bg-cream">
                  @if ($collection->image)
                    <img src="{{ $collection->image->url }}" alt="{{ $collection->title }}" class="h-full w-full object-cover">
                  @else
                    <div class="flex h-full w-full items-center justify-center text-muted">
                      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    </div>
                  @endif
                </div>
              </td>
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $collection->title }}</p>
                @if ($collection->label)
                  <p class="mt-0.5 font-body text-[12px] text-muted">{{ $collection->label }}</p>
                @endif
                <p class="mt-0.5 max-w-[280px] truncate font-body text-[12px] text-muted">{{ $collection->description }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $collection->products_count }} ürün</td>
              <td class="font-body text-[13px] text-ink">{{ $collection->number ?? 0 }}</td>
              <td>
                <span class="inline-flex rounded-full px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.04em] {{ $collection->status === \App\Enums\Status::ACTIVE ? 'bg-[rgba(21,128,61,0.1)] text-[#15803d]' : 'bg-cream text-muted' }}">
                  {{ $collection->status->label() }}
                </span>
              </td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.collectionEditPage', $collection->id) }}"
                     class="rounded-lg bg-cream px-3 py-2 font-body text-[12px] font-bold text-ink transition-colors hover:bg-hover">
                    Düzenle
                  </a>
                  <a href="{{ route('admin.collectionDelete', $collection->id) }}"
                     onclick="return confirm('Bu koleksiyonu silmek istediğinize emin misiniz?')"
                     class="rounded-lg border border-danger/30 px-3 py-2 font-body text-[12px] font-bold text-danger transition-colors hover:bg-danger/5">
                    Sil
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz koleksiyon bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($collections->hasPages())
      <div class="border-t border-ink/10 px-4 py-3.5">
        {{ $collections->links() }}
      </div>
    @endif
  </div>
@endsection
