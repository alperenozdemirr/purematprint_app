@extends('admin.layout')
@section('title', 'Blog Düzenle')
@section('page_title', 'Blog Düzenle')
@section('breadcrumb', 'İçerik / Blog / Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.blogList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Blog Düzenle</h2>
      <p class="font-body text-[13px] text-muted">{{ $blog->title }}</p>
    </div>
  </div>

  <form action="{{ route('admin.blogUpdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $blog->id }}">

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Yazı İçeriği</h3>
          </div>
          <div class="grid grid-cols-1 gap-5 p-5">
            <div>
              <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Başlık <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}" required
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="subtitle" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kategori / Alt Başlık <span class="text-danger">*</span></label>
              <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $blog->subtitle) }}" required
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              @error('subtitle') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
              <p class="mb-1.5 font-body text-[12px] text-muted">Slug: <span class="font-semibold text-ink">{{ $blog->slug }}</span></p>
              <label for="content" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İçerik <span class="text-danger">*</span></label>
              <textarea id="content" name="content" rows="12" required
                        class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('content', $blog->content) }}</textarea>
              @error('content') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>
          </div>
        </section>
      </div>

      <aside class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Kapak Görseli</h3>
          </div>
          <div class="p-5">
            @if ($blog->image)
              <div class="mb-4 overflow-hidden rounded-lg border border-ink/10">
                <img src="{{ $blog->image->url }}" alt="{{ $blog->title }}" class="h-40 w-full object-cover">
              </div>
            @endif
            <label for="image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yeni Görsel (opsiyonel)</label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[13px] text-ink file:mr-3 file:rounded-md file:border-0 file:bg-ink file:px-3 file:py-1.5 file:font-body file:text-[12px] file:font-bold file:text-on-dark">
            <p class="mt-2 font-body text-[12px] text-muted">Maks. 2 MB, JPG/PNG/WebP</p>
            @error('image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </section>

        <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
          Değişiklikleri Kaydet
        </button>
      </aside>
    </div>
  </form>
@endsection

@include('admin.partials.ckeditor')
