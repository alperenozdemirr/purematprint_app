@extends('admin.layout')
@section('title', 'Bannerlar')
@section('page_title', 'Bannerlar')
@section('breadcrumb', 'İçerik / Bannerlar')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Banner Listesi</h2>
      <p class="font-body text-[13px] text-muted">Toplam <span class="font-bold text-ink">{{ $banners->total() }}</span> banner</p>
    </div>
    <a href="{{ route('admin.bannerStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Banner
    </a>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Görsel</th>
            <th>Başlık</th>
            <th>Alt Başlık</th>
            <th>Yönlendirme</th>
            <th>Sıra</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($banners as $banner)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <div class="h-14 w-24 overflow-hidden rounded-lg bg-cream">
                  @if ($banner->image)
                    <img src="{{ $banner->image->url }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                  @else
                    <div class="flex h-full w-full items-center justify-center text-muted">
                      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                  @endif
                </div>
              </td>
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $banner->title }}</p>
                <p class="mt-0.5 max-w-[240px] truncate font-body text-[12px] text-muted">{{ $banner->description }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">{{ $banner->sub_title }}</td>
              <td class="font-body text-[13px] text-ink">
                @if ($banner->redirect_url)
                  <a href="{{ $banner->redirect_url }}" target="_blank" rel="noopener" class="text-accent hover:underline">{{ Str::limit($banner->redirect_url, 40) }}</a>
                @else
                  —
                @endif
              </td>
              <td class="font-body text-[13px] text-ink">{{ $banner->number ?? 0 }}</td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.bannerEditPage', $banner->id) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                  </a>
                  <a href="{{ route('admin.bannerDelete', $banner->id) }}" aria-label="Sil" onclick="return confirm('Bu bannerı silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz banner bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($banners->hasPages())
      <div class="border-t border-ink/10 px-4 py-3.5">
        {{ $banners->links() }}
      </div>
    @endif
  </div>
@endsection
