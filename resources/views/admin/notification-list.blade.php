@extends('admin.layout')
@section('title', 'Bildirimler')
@section('page_title', 'Bildirimler')
@section('breadcrumb', 'Sistem / Bildirimler')

@section('content')
  <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Bildirimler</h2>
      <p class="font-body text-[13px] text-muted">Sipariş ve tasarım olaylarını buradan takip edin.</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <form action="{{ route('admin.notificationMarkAllRead') }}" method="POST">
        @csrf
        <button type="submit" class="inline-flex items-center rounded-lg border border-ink/15 bg-surface px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-ink hover:bg-hover">Tümünü okundu yap</button>
      </form>
      <form action="{{ route('admin.notificationDestroyAll') }}" method="POST" onsubmit="return confirm('Tüm bildirimler silinsin mi?')">
        @csrf
        <button type="submit" class="inline-flex items-center rounded-lg bg-danger px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-on-dark hover:opacity-90">Tümünü sil</button>
      </form>
    </div>
  </div>

  <form action="{{ route('admin.notificationBulkDestroy') }}" method="POST" data-notification-bulk>
    @csrf
    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <label class="inline-flex items-center gap-2 cursor-pointer">
          <input type="checkbox" data-select-all class="h-4 w-4 accent-accent">
          <span class="font-body text-[13px] font-semibold text-ink">Tümünü seç</span>
        </label>
        <button type="submit" onclick="return confirm('Seçili bildirimler silinsin mi?')"
                class="inline-flex items-center rounded-lg border border-danger/30 bg-danger/5 px-4 py-2 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-danger hover:bg-danger hover:text-on-dark">
          Seçilenleri sil
        </button>
      </div>

      @if ($notifications->isEmpty())
        <div class="px-5 py-12 text-center font-body text-[14px] text-muted">Bildirim bulunmuyor.</div>
      @else
        <ul class="divide-y divide-ink/8">
          @foreach ($notifications as $notification)
            <li class="flex flex-wrap items-start gap-3 px-5 py-4 {{ $notification->isRead() ? '' : 'bg-accent/5' }}">
              <label class="mt-1 cursor-pointer">
                <input type="checkbox" name="ids[]" value="{{ $notification->id }}" data-select-item class="h-4 w-4 accent-accent">
              </label>
              <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="inline-flex rounded-md bg-cream px-2 py-0.5 font-body text-[10px] font-bold uppercase tracking-[0.06em] text-accent">{{ $notification->type?->label() }}</span>
                  @unless ($notification->isRead())
                    <span class="inline-flex rounded-md bg-badge-sale/15 px-2 py-0.5 font-body text-[10px] font-bold uppercase text-badge-sale">Yeni</span>
                  @endunless
                  <span class="font-body text-[11px] text-muted">{{ $notification->created_at?->format('d.m.Y H:i') }}</span>
                </div>
                <p class="mt-1.5 font-body text-[15px] font-semibold text-ink m-0">{{ $notification->title }}</p>
                @if (filled($notification->body))
                  <p class="mt-1 font-body text-[13px] text-muted m-0">{{ $notification->body }}</p>
                @endif
                <div class="mt-3 flex flex-wrap gap-2">
                  <a href="{{ route('admin.notificationOpen', $notification->id) }}"
                     class="inline-flex items-center rounded-lg bg-accent px-3 py-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-on-dark hover:bg-accent-dark">
                    {{ $notification->order ? 'Siparişi Aç' : 'Görüntüle' }}
                  </a>
                </div>
              </div>
              <form action="{{ route('admin.notificationDestroy', $notification->id) }}" method="POST" onsubmit="return confirm('Bu bildirim silinsin mi?')">
                @csrf
                <button type="submit" class="rounded-lg bg-cream px-3 py-2 font-body text-[11px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
              </form>
            </li>
          @endforeach
        </ul>
      @endif
    </section>
  </form>

  <div class="mt-5">
    {{ $notifications->links() }}
  </div>
@endsection

@section('scripts')
  <script>
    (function () {
      const form = document.querySelector('[data-notification-bulk]');
      if (!form) return;
      const selectAll = form.querySelector('[data-select-all]');
      const items = () => form.querySelectorAll('[data-select-item]');
      selectAll?.addEventListener('change', () => {
        items().forEach((el) => { el.checked = selectAll.checked; });
      });
    })();
  </script>
@endsection
