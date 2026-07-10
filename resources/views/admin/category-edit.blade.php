@extends('admin.layout')
@section('title', 'Kategori Düzenle')
@section('page_title', 'Kategori Düzenle')
@section('breadcrumb', 'Katalog / Kategoriler / Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.categoryList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $category->name }}</h2>
      <p class="font-body text-[13px] text-muted">{{ $category->slug }}</p>
    </div>
  </div>

  <form action="{{ route('admin.categoryUpdate') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $category->id }}">

    <div class="mx-auto max-w-2xl overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Kategori Bilgileri</h3>
      </div>
      <div class="grid grid-cols-1 gap-5 p-5">
        <div>
          <label for="name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kategori Adı <span class="text-danger">*</span></label>
          <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('name') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">Slug</label>
          <p class="rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted">{{ $category->slug }} <span class="text-[12px]">(ad değişince otomatik güncellenir)</span></p>
        </div>

        <div>
          <label for="parent_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Üst Kategori</label>
          <select id="parent_id" name="parent_id" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
            <option value="">Ana kategori (üst yok)</option>
            @foreach ($parentCategories as $parentCategory)
              <option value="{{ $parentCategory->id }}" @selected(old('parent_id', $category->parent_id) == $parentCategory->id)>{{ $parentCategory->name }}</option>
            @endforeach
          </select>
          @error('parent_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="number" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sıra Numarası</label>
          <input type="number" min="0" id="number" name="number" value="{{ old('number', $category->number ?? 0) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('number') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="flex flex-col gap-3 border-t border-ink/10 p-5 sm:flex-row">
        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Değişiklikleri Kaydet
        </button>
        <a href="{{ route('admin.categoryList') }}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
          İptal
        </a>
      </div>
    </div>
  </form>
@endsection
