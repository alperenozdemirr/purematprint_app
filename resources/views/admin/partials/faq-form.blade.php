@php
  $faq = $faq ?? null;
@endphp

<form action="{{ $action }}" method="POST">
  @csrf
  @if ($faq)
    <input type="hidden" name="id" value="{{ $faq->id }}">
  @endif

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Soru & Cevap</h3>
      </div>
      <div class="grid gap-5 p-5">
        <div>
          <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Soru Başlığı <span class="text-danger">*</span></label>
          <input type="text" id="title" name="title" value="{{ old('title', $faq?->title) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="Örn. Teslimat süresi ne kadar?">
          @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="content" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Cevap <span class="text-danger">*</span></label>
          <textarea id="content" name="content" rows="10" required
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="Sorunun cevabını yazın">{{ old('content', $faq?->content) }}</textarea>
          @error('content') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <aside class="flex flex-col gap-6">
      <section class="overflow-hidden rounded-xl bg-surface shadow-card">
        <div class="border-b border-ink/10 px-5 py-4">
          <h3 class="font-heading text-[16px] font-bold text-ink">Ayarlar</h3>
        </div>
        <div class="grid gap-5 p-5">
          <div>
            <label for="group_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Grup <span class="text-danger">*</span></label>
            <select id="group_id" name="group_id" required
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              <option value="">Grup seçin</option>
              @foreach ($groupOptions as $groupOption)
                <option value="{{ $groupOption->id }}" @selected((int) old('group_id', $selectedGroupId) === (int) $groupOption->id)>
                  {{ $groupOption->title }}
                </option>
              @endforeach
            </select>
            @error('group_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="number" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sıra Numarası</label>
            <input type="number" min="0" id="number" name="number" value="{{ old('number', $faq?->number) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
            @error('number') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <label class="flex items-start gap-3 rounded-lg border border-ink/10 bg-cream px-3.5 py-3 cursor-pointer">
            <input type="checkbox" name="fixed_status" value="1" @checked(old('fixed_status', $faq?->fixed_status))
                   class="mt-0.5 accent-accent">
            <span>
              <span class="block font-body text-[13px] font-bold text-ink">Anasayfada göster</span>
              <span class="mt-1 block font-body text-[12px] leading-relaxed text-muted">İşaretlenirse bu soru anasayfadaki SSS alanında listelenir.</span>
            </span>
          </label>
        </div>
      </section>

      <div class="flex flex-col gap-3">
        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          {{ $submitLabel }}
        </button>
        <a href="{{ $cancelUrl }}" class="inline-flex w-full items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
          İptal
        </a>
      </div>
    </aside>
  </div>
</form>
