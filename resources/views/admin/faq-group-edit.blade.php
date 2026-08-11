@extends('admin.layout')
@section('title', 'SSS Grubu Düzenle')
@section('page_title', 'SSS Grubu Düzenle')
@section('breadcrumb', 'İçerik / SSS / Düzenle')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.faqGroupList') }}" aria-label="Geri"
         class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $faqGroup->title }}</h2>
        <p class="font-body text-[13px] text-muted">{{ $faqGroup->faqs->count() }} soru</p>
      </div>
    </div>
    <a href="{{ route('admin.faqStorePage', ['group_id' => $faqGroup->id]) }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Soru
    </a>
  </div>

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_1fr]">
    <form action="{{ route('admin.faqGroupUpdate') }}" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{ $faqGroup->id }}">

      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Grup Bilgileri</h3>
        </div>
        <div class="grid gap-5 p-5">
          <div>
            <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Grup Adı <span class="text-danger">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $faqGroup->title) }}" required
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
            @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="number" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sıra Numarası</label>
            <input type="number" min="0" id="number" name="number" value="{{ old('number', $faqGroup->number) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
            @error('number') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
            Grubu Güncelle
          </button>
        </div>
      </section>
    </form>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Gruptaki Sorular</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] border-collapse text-left">
          <thead>
            <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
              <th>Soru</th>
              <th>Anasayfa</th>
              <th>Sıra</th>
              <th class="text-right">İşlemler</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
            @forelse ($faqGroup->faqs as $faq)
              <tr class="transition-colors hover:bg-hover/60">
                <td class="max-w-[360px] font-body text-[14px] font-semibold text-ink">{{ $faq->title }}</td>
                <td>
                  @if ($faq->fixed_status)
                    <span class="inline-flex rounded-full bg-accent/15 px-2.5 py-1 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-accent">Sabit</span>
                  @else
                    <span class="font-body text-[13px] text-muted">—</span>
                  @endif
                </td>
                <td class="font-body text-[13px] text-ink">{{ $faq->number ?? '—' }}</td>
                <td>
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.faqEditPage', $faq->id) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                    </a>
                    <a href="{{ route('admin.faqDelete', $faq->id) }}" aria-label="Sil" onclick="return confirm('Bu soruyu silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-10 text-center font-body text-[14px] text-muted">Bu grupta henüz soru yok.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
