@extends('admin.layout')
@section('title', 'Demo Yorumlar')
@section('page_title', 'Demo Yorumlar')
@section('breadcrumb', 'İçerik / Demo Yorumlar')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Anasayfa Demo Yorumları</h2>
      <p class="font-body text-[13px] text-muted">
        Ayarlarda gerçek yorumlar kapalıyken anasayfada gösterilir · Toplam <span class="font-bold text-ink">{{ $reviews->count() }}</span> kayıt
      </p>
    </div>
    <a href="{{ route('admin.homepageDemoReviewStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Demo Yorum
    </a>
  </div>

  @if (session('success'))
    <div class="mb-5 rounded-xl border border-success/20 bg-success/5 px-4 py-3">
      <p class="text-sm font-medium text-success">{{ session('success') }}</p>
    </div>
  @endif

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[760px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Yorum</th>
            <th>Yazar</th>
            <th>Yıldız</th>
            <th>Durum</th>
            <th class="w-28"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($reviews as $review)
            <tr class="transition-colors hover:bg-hover/60">
              <td class="max-w-[360px] font-body text-[14px] text-ink">
                <p class="m-0 line-clamp-2">{{ $review->quote }}</p>
              </td>
              <td class="font-body text-[13px] text-muted whitespace-nowrap">{{ $review->author }}</td>
              <td class="font-body text-[13px] text-muted">{{ $review->stars }}/5</td>
              <td>
                @if ($review->is_visible)
                  <span class="inline-flex rounded-full bg-success/10 px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-success">Aktif</span>
                @else
                  <span class="inline-flex rounded-full bg-cream px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Gizli</span>
                @endif
              </td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.homepageDemoReviewEditPage', $review->id) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                  </a>
                  <a href="{{ route('admin.homepageDemoReviewDelete', $review->id) }}" aria-label="Sil" onclick="return confirm('Bu demo yorumu silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz demo yorum eklenmemiş.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
