@php
  $review = $review ?? null;
@endphp

<div class="mx-auto max-w-2xl overflow-hidden rounded-xl bg-surface shadow-card">
  <div class="border-b border-ink/10 px-5 py-4">
    <h3 class="font-heading text-[16px] font-bold text-ink">Yorum Bilgileri</h3>
  </div>
  <div class="grid grid-cols-1 gap-5 p-5">
    <div>
      <label for="quote" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yorum Metni <span class="text-danger">*</span></label>
      <textarea id="quote" name="quote" rows="4" required
                class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('quote', $review?->quote) }}</textarea>
      @error('quote') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="author" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yazar <span class="text-danger">*</span></label>
      <input type="text" id="author" name="author" value="{{ old('author', $review?->author) }}" required
             class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
             placeholder="Örn. Elif Yılmaz, Studio Noir">
      @error('author') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="stars" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yıldız</label>
      <select id="stars" name="stars"
              class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
        @for ($star = 5; $star >= 1; $star--)
          <option value="{{ $star }}" @selected((int) old('stars', $review?->stars ?? 5) === $star)>{{ $star }} yıldız</option>
        @endfor
      </select>
      @error('stars') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Görsel</label>
      @if ($review?->image_id && $review?->image)
        <img src="{{ $review->image->url }}" alt="" class="mb-3 h-24 w-auto max-w-[280px] rounded-lg border border-ink/10 bg-cream object-cover p-1">
      @endif
      <input type="file" id="image" name="image" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}"
             class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
      @error('image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-3 cursor-pointer">
      <input type="hidden" name="is_visible" value="0">
      <input type="checkbox" name="is_visible" value="1" class="h-4 w-4 accent-accent" @checked(old('is_visible', $review?->is_visible ?? true))>
      <span class="font-body text-[14px] text-ink">Anasayfada göster</span>
    </label>
  </div>

  <div class="flex flex-col gap-3 border-t border-ink/10 p-5 sm:flex-row">
    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      Kaydet
    </button>
    <a href="{{ route('admin.homepageDemoReviewList') }}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
      İptal
    </a>
  </div>
</div>
