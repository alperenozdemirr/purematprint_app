@extends('admin.layout')
@section('title', 'Koleksiyon Düzenle')
@section('page_title', 'Koleksiyon Düzenle')
@section('breadcrumb', 'Katalog / Koleksiyonlar / Düzenle')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.collectionList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">{{ $collection->title }}</h2>
      <p class="font-body text-[13px] text-muted">Koleksiyon bilgilerini ve ürün seçimini güncelleyin</p>
    </div>
  </div>

  <form action="{{ route('admin.collectionUpdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $collection->id }}">

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Koleksiyon Bilgileri</h3>
          </div>
          <div class="grid grid-cols-1 gap-5 p-5">
            <div>
              <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Başlık <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" value="{{ old('title', $collection->title) }}" required
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="label" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Etiket</label>
              <input type="text" id="label" name="label" value="{{ old('label', $collection->label) }}"
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              @error('label') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="description" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Açıklama</label>
              <textarea id="description" name="description" rows="4"
                        class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('description', $collection->description) }}</textarea>
              @error('description') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div>
                <label for="number" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Sıra Numarası</label>
                <input type="number" min="0" id="number" name="number" value="{{ old('number', $collection->number) }}"
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
                @error('number') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>

              <div>
                <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Durum <span class="text-danger">*</span></label>
                <select id="status" name="status" required
                        class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
                  @foreach (\App\Enums\Status::cases() as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $collection->status->value) === $status->value)>{{ $status->label() }}</option>
                  @endforeach
                </select>
                @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>
            </div>

            <p class="font-body text-[12px] text-muted">Slug: <span class="font-semibold text-ink">{{ $collection->slug }}</span></p>
          </div>
        </section>

        @include('admin.partials.collection-product-picker', [
            'products' => $products,
            'selectedProductIds' => old('product_ids', $selectedProductIds),
        ])
      </div>

      <aside class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Koleksiyon Görseli</h3>
          </div>
          <div class="p-5">
            @if ($collection->image)
              <div class="mb-4 overflow-hidden rounded-lg bg-cream">
                <img src="{{ $collection->image->url }}" alt="{{ $collection->title }}" class="aspect-[16/10] w-full object-cover">
              </div>
            @endif
            <label for="image" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yeni Görsel Yükle</label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[13px] text-ink file:mr-3 file:rounded-md file:border-0 file:bg-ink file:px-3 file:py-1.5 file:font-body file:text-[12px] file:font-bold file:text-on-dark">
            <p class="mt-2 font-body text-[12px] text-muted">Boş bırakırsanız mevcut görsel korunur</p>
            @error('image') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </section>

        <div class="flex flex-col gap-3">
          <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
            Değişiklikleri Kaydet
          </button>
          <a href="{{ route('admin.collectionList') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
            Listeye Dön
          </a>
        </div>
      </aside>
    </div>
  </form>
@endsection
