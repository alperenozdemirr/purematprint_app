@extends('admin.layout')
@section('title', 'Yorumlar')
@section('page_title', 'Yorumlar')
@section('breadcrumb', 'Satış / Yorumlar')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Ürün Yorumları</h2>
    <p class="font-body text-[13px] text-muted">Kullanıcı değerlendirmelerini onaylayın veya gizleyin</p>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[980px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th>Kullanıcı</th>
            <th>Ürün</th>
            <th>Puan</th>
            <th>Yorum</th>
            <th>Sipariş</th>
            <th>Durum</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($comments as $comment)
            <tr class="transition-colors hover:bg-hover/60">
              <td>
                <p class="font-body text-[14px] font-bold text-ink">{{ $comment->user?->name ?? '—' }}</p>
                <p class="font-body text-[12px] text-muted">{{ $comment->user?->email }}</p>
              </td>
              <td>
                <p class="max-w-[180px] truncate font-body text-[14px] font-semibold text-ink">{{ $comment->product?->title ?? '—' }}</p>
              </td>
              <td>
                <span class="font-body text-[14px] font-bold text-[#f59e0b]">{{ number_format((float) $comment->rating, 1) }} ★</span>
              </td>
              <td>
                <p class="max-w-[260px] font-body text-[13px] text-ink">{{ $comment->content }}</p>
                <p class="mt-1 font-body text-[11px] text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</p>
              </td>
              <td class="font-body text-[13px] text-ink">
                {{ $comment->orderDetail?->order?->code ?? '—' }}
              </td>
              <td>
                @if ($comment->is_visible)
                  <span class="inline-flex rounded-full bg-[rgba(21,128,61,0.1)] px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-[#15803d]">Yayında</span>
                @else
                  <span class="inline-flex rounded-full bg-[#fff8e6] px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-[#92400e]">Onay Bekliyor</span>
                @endif
              </td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  @if (! $comment->is_visible)
                    <form action="{{ route('admin.commentUpdate') }}" method="POST">
                      @csrf
                      <input type="hidden" name="id" value="{{ $comment->id }}">
                      <input type="hidden" name="is_visible" value="1">
                      <button type="submit" class="rounded-lg bg-accent px-3 py-2 font-body text-[12px] font-bold text-on-dark transition-colors hover:bg-accent-dark">
                        Onayla
                      </button>
                    </form>
                  @else
                    <form action="{{ route('admin.commentUpdate') }}" method="POST">
                      @csrf
                      <input type="hidden" name="id" value="{{ $comment->id }}">
                      <input type="hidden" name="is_visible" value="0">
                      <button type="submit" class="rounded-lg bg-cream px-3 py-2 font-body text-[12px] font-bold text-ink transition-colors hover:bg-hover">
                        Gizle
                      </button>
                    </form>
                  @endif
                  <a href="{{ route('admin.commentDelete', $comment->id) }}"
                     onclick="return confirm('Bu yorumu silmek istediğinize emin misiniz?')"
                     class="rounded-lg border border-danger/30 px-3 py-2 font-body text-[12px] font-bold text-danger transition-colors hover:bg-danger/5">
                    Sil
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz yorum bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($comments->hasPages())
      <div class="border-t border-ink/10 px-4 py-3.5">
        {{ $comments->links() }}
      </div>
    @endif
  </div>
@endsection
