@extends('admin.layout')
@section('title', 'Yeni Ürün')
@section('page_title', 'Yeni Ürün')
@section('breadcrumb', 'Katalog / Ürünler / Yeni Ürün')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.productList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Yeni Ürün Ekle</h2>
      <p class="font-body text-[13px] text-muted">Ürün bilgilerini doldurup kaydedin</p>
    </div>
  </div>

  <form action="{{ route('admin.productStore') }}" method="POST" enctype="multipart/form-data" data-product-form>
    @csrf

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Genel Bilgiler</h3>
          </div>
          <div class="grid grid-cols-1 gap-5 p-5">
            <div>
              <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Adı <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" value="{{ old('title') }}" required
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                     placeholder="Örn. LED Lightbox Tabela">
              @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div>
                <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Kodu</label>
                <p class="rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted">Kayıt sırasında otomatik oluşturulur</p>
              </div>
              <div>
                <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">Slug</label>
                <p class="rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted">Ürün adı ve kodundan otomatik oluşturulur</p>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div>
                <label for="price" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Fiyat (₺) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}" required
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                       placeholder="0.00">
                @error('price') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>
              <div>
                <label for="stock_count" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Stok Adedi</label>
                <input type="number" min="0" id="stock_count" name="stock_count" value="{{ old('stock_count', 0) }}"
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                       placeholder="0">
                @error('stock_count') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Açıklama</h3>
          </div>
          <div class="p-5">
            <div>
              <label for="description" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Açıklaması</label>
              <textarea id="description" name="description" rows="5"
                        class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                        placeholder="Ürünün detaylı açıklaması...">{{ old('description') }}</textarea>
              @error('description') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Ürün Görselleri</h3>
          </div>
          <div class="p-5">
            <label for="images"
                   class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-ink/15 bg-cream px-4 py-10 text-center transition-colors hover:border-accent/40 hover:bg-hover/50">
              <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-muted"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
              <span class="font-body text-[14px] font-bold text-ink">Görselleri sürükleyin veya seçin</span>
              <span class="font-body text-[12px] text-muted">PNG, JPG, WEBP — en fazla 2MB. İlk görsel kapak olur.</span>
              <input type="file" id="images" name="images[]" accept="image/*" multiple class="hidden" data-image-input>
            </label>
            @error('images') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            @error('images.*') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            <div class="mt-4 hidden grid-cols-3 gap-3 sm:grid-cols-4" data-image-preview></div>
          </div>
        </section>
      </div>

      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Yayın</h3>
          </div>
          <div class="flex flex-col gap-5 p-5">
            <div>
              <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Durum</label>
              <select id="status" name="status" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
                <option value="active" @selected(old('status') === 'active')>Aktif</option>
                <option value="passive" @selected(old('status') === 'passive')>Pasif</option>
              </select>
              @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg bg-cream px-3.5 py-3 transition-colors hover:bg-hover">
              <span>
                <span class="block font-body text-[13px] font-bold text-ink">Öne Çıkan Ürün</span>
                <span class="block font-body text-[12px] text-muted">Anasayfada vitrine çıkar</span>
              </span>
              <input type="checkbox" name="featured_status" value="1" @checked(old('featured_status')) class="h-5 w-5 shrink-0 accent-accent">
            </label>

            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg bg-cream px-3.5 py-3 transition-colors hover:bg-hover">
              <span>
                <span class="block font-body text-[13px] font-bold text-ink">Tanıtım Ürünü</span>
                <span class="block font-body text-[12px] text-muted">Koleksiyon tanıtımı için kullan</span>
              </span>
              <input type="checkbox" name="introduction_status" value="1" @checked(old('introduction_status')) class="h-5 w-5 shrink-0 accent-accent">
            </label>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Kategori</h3>
          </div>
          <div class="p-5">
            <label for="category_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kategori Seç <span class="text-danger">*</span></label>
            <select id="category_id" name="category_id" required class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              <option value="">Kategori seçin...</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </section>

        <div class="flex flex-col gap-3 rounded-xl bg-surface p-5 shadow-card">
          <button type="submit"
                  class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2zM17 21v-8H7v8M7 3v5h8"/></svg>
            Ürünü Kaydet
          </button>
          <a href="{{ route('admin.productList') }}"
             class="inline-flex items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
            İptal
          </a>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('scripts')
  <script>
    (function () {
      const form = document.querySelector('[data-product-form]');
      if (!form) return;

      const imageInput = form.querySelector('[data-image-input]');
      const preview = form.querySelector('[data-image-preview]');
      imageInput.addEventListener('change', () => {
        preview.innerHTML = '';
        const files = Array.from(imageInput.files);
        if (!files.length) { preview.classList.add('hidden'); return; }
        preview.classList.remove('hidden');
        files.forEach((file, i) => {
          const url = URL.createObjectURL(file);
          const cell = document.createElement('div');
          cell.className = 'relative aspect-square overflow-hidden rounded-lg bg-cream';
          cell.innerHTML = '<img src="' + url + '" class="h-full w-full object-cover" alt="">' +
            (i === 0 ? '<span class="absolute left-1 top-1 rounded bg-accent px-1.5 py-0.5 font-body text-[10px] font-bold uppercase text-on-dark">Kapak</span>' : '');
          preview.appendChild(cell);
        });
      });
    })();
  </script>
@endsection
