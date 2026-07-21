@extends('admin.layout')
@section('title', 'Blog Yazıları')
@section('page_title', 'Blog Yazıları')
@section('breadcrumb', 'İçerik / Blog')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Blog Listesi</h2>
      <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $blogs->total() }}</span> yazı</p>
    </div>
    <a href="{{ route('admin.blogStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Yazı
    </a>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Görsel</th>
            <th>Başlık</th>
            <th>Kategori</th>
            <th>Slug</th>
            <th>Tarih</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($blogs as $blog)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <div class="h-14 w-24 overflow-hidden rounded-lg bg-cream">
                  @if ($blog->image)
                    <img src="{{ $blog->image->url }}" alt="{{ $blog->title }}" class="h-full w-full object-cover">
                  @else
                    <div class="flex h-full w-full items-center justify-center text-muted">
                      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                  @endif
                </div>
              </td>
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $blog->title }}</p>
                <p class="mt-0.5 max-w-[280px] truncate font-body text-[12px] text-muted">{{ $blog->excerpt }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $blog->subtitle }}</td>
              <td class="font-body text-[12px] text-muted">{{ $blog->slug }}</td>
              <td class="font-body text-[13px] text-ink">{{ $blog->created_at?->format('d.m.Y') }}</td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('blogShow', $blog->slug) }}" target="_blank" rel="noopener"
                     class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-ink/10 bg-cream text-ink transition-colors hover:bg-hover" title="Görüntüle">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                  <a href="{{ route('admin.blogEditPage', $blog->id) }}"
                     class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-ink/10 bg-cream text-ink transition-colors hover:bg-hover" title="Düzenle">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  <a href="{{ route('admin.blogDelete', $blog->id) }}"
                     onclick="return confirm('Bu blog yazısını silmek istediğinize emin misiniz?')"
                     class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-danger/20 bg-danger/5 text-danger transition-colors hover:bg-danger/10" title="Sil">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz blog yazısı eklenmemiş.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($blogs->hasPages())
      <div class="border-t border-ink/8 px-4 py-4">
        {{ $blogs->links() }}
      </div>
    @endif
  </div>
@endsection
