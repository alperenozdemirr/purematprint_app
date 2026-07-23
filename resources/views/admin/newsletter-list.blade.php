@extends('admin.layout')
@section('title', 'Bülten')
@section('page_title', 'Bülten Yönetimi')
@section('breadcrumb', 'İçerik / Bülten')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Bülten Aboneleri</h2>
    <p class="font-body text-[13px] text-muted">
      Toplam <span class="font-bold text-ink">{{ $subscriberCount }}</span> abone
    </p>
  </div>

  @if (session('success'))
    <div class="mb-5 rounded-xl border border-success/20 bg-success/5 px-4 py-3">
      <p class="text-sm font-medium text-success">{{ session('success') }}</p>
    </div>
  @endif
  @if (session('error'))
    <div class="mb-5 rounded-xl border border-danger/20 bg-danger/5 px-4 py-3">
      <p class="text-sm font-medium text-danger">{{ session('error') }}</p>
    </div>
  @endif

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_420px]">
    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Kayıtlı E-postalar</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[480px] border-collapse text-left">
          <thead>
            <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
              <th>E-posta</th>
              <th>Kayıt Tarihi</th>
              <th class="text-right">İşlem</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3">
            @forelse ($subscribers as $subscriber)
              <tr class="transition-colors hover:bg-hover/60">
                <td class="font-body text-[14px] font-semibold text-ink">{{ $subscriber->email }}</td>
                <td class="font-body text-[13px] text-muted">{{ $subscriber->created_at?->format('d.m.Y H:i') }}</td>
                <td class="text-right">
                  <a href="{{ route('admin.newsletterDelete', $subscriber->id) }}" aria-label="Sil" onclick="return confirm('Bu aboneyi silmek istediğinize emin misiniz?')" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz bülten abonesi yok.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($subscribers->hasPages())
        <div class="border-t border-ink/10 px-5 py-4">
          {{ $subscribers->links() }}
        </div>
      @endif
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card h-fit">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Toplu Bildirim Gönder</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Tüm abonelere e-posta gönderilir.</p>
      </div>
      <form action="{{ route('admin.newsletterBroadcast') }}" method="POST" class="grid gap-5 p-5">
        @csrf
        <div>
          <label for="subject" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Konu</label>
          <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="Örn. Yeni koleksiyon duyurusu">
          @error('subject') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="content" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İçerik</label>
          <textarea id="content" name="content" rows="10" required
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('content') }}</textarea>
          @error('content') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <button type="submit" onclick="return confirm('{{ $subscriberCount }} aboneye e-posta gönderilecek. Devam edilsin mi?')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark {{ $subscriberCount === 0 ? 'pointer-events-none opacity-50' : '' }}"
                {{ $subscriberCount === 0 ? 'disabled' : '' }}>
          Toplu Gönder
        </button>
      </form>
    </section>
  </div>
@endsection

@include('admin.partials.ckeditor')
