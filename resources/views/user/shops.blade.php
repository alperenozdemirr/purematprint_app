@extends('user.layout')
@section('title','Tüm Ürünler')
@section('content')
  <section class="py-10 pb-20 pt-6 min-[768px]:pt-7" data-i5="shop--catalog" data-i5-tags="shop shop--catalog">
    <div class="w-full max-w-site min-[1024px]:max-w-catalog mx-auto px-5 lg:px-8" data-i5="container">
      <div class="mb-5" data-i5="shop__head">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum" data-i5="breadcrumb">
          <a href="{{ route('index') }}">Anasayfa</a>
          <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
          <span>@if (request('q')) Arama @else Tüm Ürünler @endif</span>
        </nav>
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case" data-i5="shop__title">
          @if (request('q'))
            “{{ request('q') }}” için sonuçlar
          @else
            Tüm Ürünler
          @endif
        </h1>
        @if (request('q'))
          <p class="mt-2 font-body text-[14px] text-muted">{{ $products->total() }} ürün bulundu</p>
        @endif
      </div>

      @if (request('q') && ($searchCategories->isNotEmpty() || $searchCollections->isNotEmpty()))
      <div class="mb-8 grid gap-4 min-[768px]:grid-cols-2">
        @if ($searchCategories->isNotEmpty())
        <div class="border-[3px] border-ink bg-surface p-5 shadow-brutal-sm">
          <p class="mb-3 font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Eşleşen Kategoriler</p>
          <div class="flex flex-wrap gap-2">
            @foreach ($searchCategories as $searchCategory)
            <a href="{{ route('shops', ['kategori' => $searchCategory->slug]) }}" class="inline-flex items-center px-3.5 py-2 font-body text-[12px] font-bold uppercase tracking-[0.04em] border-2 border-ink bg-bg text-ink transition-colors hover:bg-hover">{{ $searchCategory->name }}</a>
            @endforeach
          </div>
        </div>
        @endif
        @if ($searchCollections->isNotEmpty())
        <div class="border-[3px] border-ink bg-surface p-5 shadow-brutal-sm">
          <p class="mb-3 font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Eşleşen Koleksiyonlar</p>
          <div class="flex flex-wrap gap-2">
            @foreach ($searchCollections as $searchCollection)
            <a href="{{ route('collectionShow', $searchCollection->slug) }}" class="inline-flex items-center px-3.5 py-2 font-body text-[12px] font-bold uppercase tracking-[0.04em] border-2 border-ink bg-bg text-ink transition-colors hover:bg-hover">{{ $searchCollection->title }}</a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
      @endif

      @if (session('success'))
      <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-ink" role="alert">{{ session('success') }}</div>
      @endif
      @if (session('error'))
      <div class="mb-6 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <div class="flex flex-col gap-5 mb-8 pb-6 border-b-[3px] border-ink min-[900px]:flex-row min-[900px]:items-center min-[900px]:justify-between min-[900px]:gap-6" data-i5="shop__toolbar">
        <div class="flex flex-wrap gap-2" role="tablist" aria-label="Kategori filtreleri" data-i5="filters">
          @php
            $filterClass = 'px-4 py-2.5 font-body text-[11px] font-bold tracking-[0.06em] uppercase border-2 border-action/25 bg-surface text-muted shadow-ui-sm cursor-pointer inline-block no-underline transition-all hover:bg-hover hover:text-ink hover:border-action/30 hover:-translate-x-px hover:-translate-y-px focus-visible:outline-2 focus-visible:outline-action focus-visible:outline-offset-2';
            $filterActiveClass = ' is-active bg-hover text-ink border-ink shadow-brutal-sm';
          @endphp
          <a href="{{ route('shops', array_filter(['siralama' => request('siralama'), 'q' => request('q')])) }}"
             class="{{ $filterClass }}{{ request('kategori') ? '' : $filterActiveClass }}"
             role="tab" aria-selected="{{ request('kategori') ? 'false' : 'true' }}" data-i5="filter">Tümü</a>
          @foreach ($categories as $category)
            <a href="{{ route('shops', array_filter(['kategori' => $category->slug, 'siralama' => request('siralama'), 'q' => request('q')])) }}"
               class="{{ $filterClass }}{{ request('kategori') === $category->slug ? $filterActiveClass : '' }}"
               role="tab" aria-selected="{{ request('kategori') === $category->slug ? 'true' : 'false' }}" data-i5="filter">{{ $category->name }}</a>
          @endforeach
        </div>

        <form method="get" action="{{ route('shops') }}" class="flex items-center gap-4 shrink-0 max-[899px]:w-full max-[899px]:justify-between max-[899px]:flex-col max-[899px]:items-stretch max-[899px]:gap-2.5" data-i5="shop__meta">
          @if (request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
          @endif
          @if (request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
          @endif
          <span class="text-[13px] font-semibold text-muted whitespace-nowrap" data-i5="shop__count">{{ $products->total() }} ürün</span>
          <div class="relative max-[899px]:w-full after:absolute after:right-[14px] after:top-1/2 after:-translate-y-1/2 after:w-0 after:h-0 after:border-l-[4px] after:border-r-[4px] after:border-t-[5px] after:border-l-transparent after:border-r-transparent after:border-t-ink after:pointer-events-none" data-i5="shop__sort">
            <label for="product-sort" class="hidden">Sırala</label>
            <select id="product-sort" name="siralama" onchange="this.form.submit()"
                    class="px-3.5 py-2.5 border-[3px] border-ink bg-surface font-body text-[13px] font-semibold text-ink shadow-brutal-sm cursor-pointer outline-none focus:shadow-brutal w-full min-w-0 max-w-full min-[900px]:w-auto pr-9 appearance-none" aria-label="Sıralama">
              <option value="featured" @selected(request('siralama', 'featured') === 'featured')>Öne Çıkan</option>
              <option value="price-asc" @selected(request('siralama') === 'price-asc')>Fiyat: Düşük → Yüksek</option>
              <option value="price-desc" @selected(request('siralama') === 'price-desc')>Fiyat: Yüksek → Düşük</option>
              <option value="name" @selected(request('siralama') === 'name')>İsim: A → Z</option>
            </select>
          </div>
        </form>
      </div>

      <div class="grid grid-cols-2 gap-4 min-[768px]:grid-cols-3 min-[768px]:gap-5 min-[1024px]:grid-cols-4" data-i5="products">
        @forelse ($products as $product)
          @include('user.partials.product-card', ['product' => $product])
        @empty
          <div class="col-span-full border-[3px] border-ink bg-surface px-6 py-14 text-center shadow-brutal-sm">
            <p class="font-heading text-section-title font-semibold text-ink">Ürün bulunamadı</p>
            <p class="mt-2 font-body text-[15px] text-muted">Bu kategoride henüz ürün yok veya filtreyi değiştirmeyi deneyin.</p>
            @if (request('q'))
              <a href="{{ route('shops', array_filter(['kategori' => request('kategori')])) }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-surface text-ink shadow-brutal hover:bg-hover">Aramayı Temizle</a>
            @else
            <a href="{{ route('shops') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Tüm Ürünleri Gör</a>
            @endif
          </div>
        @endforelse
      </div>

      @if ($products->hasPages())
        <nav class="mt-9 flex justify-center" aria-label="Ürün sayfaları" data-i5="pagination">
          {{ $products->links() }}
        </nav>
      @endif
    </div>
  </section>
@endsection
