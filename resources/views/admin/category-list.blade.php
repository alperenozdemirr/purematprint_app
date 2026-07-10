@extends('admin.layout')
@section('title', 'Kategoriler')
@section('page_title', 'Kategoriler')
@section('breadcrumb', 'Katalog / Kategoriler')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Kategori Listesi</h2>
      <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $categories->total() }}</span> kategori</p>
    </div>
    <a href="{{ route('admin.categoryStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Kategori
    </a>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[720px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Kategori</th>
            <th>Üst Kategori</th>
            <th>Sıra</th>
            <th>Ürün</th>
            <th>Alt Kategori</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($categories as $category)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $category->name }}</p>
                <p class="mt-0.5 font-body text-[12px] text-muted">{{ $category->slug }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $category->parent?->name ?? '—' }}</td>
              <td class="font-body text-[13px] text-ink">{{ $category->number ?? 0 }}</td>
              <td class="font-body text-[13px] text-ink">{{ $category->products_count }}</td>
              <td class="font-body text-[13px] text-ink">{{ $category->children_count }}</td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.categoryEditPage', $category->slug) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                  </a>
                  <a href="{{ route('admin.categoryDelete', $category->id) }}" aria-label="Sil" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz kategori bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($categories->hasPages())
      <div class="border-t border-ink/10 px-4 py-3.5">
        {{ $categories->links() }}
      </div>
    @endif
  </div>
@endsection
