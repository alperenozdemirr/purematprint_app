@extends('user.layout')
@section('title','Tüm Ürünler')
@section('content')
  <section class="py-10 pb-20 pt-6 min-[768px]:pt-7" data-i5="shop--catalog" data-i5-tags="shop shop--catalog">
    <div class="w-full max-w-site min-[1024px]:max-w-catalog mx-auto px-5 lg:px-8" data-i5="container">
      <div class="mb-5" data-i5="shop__head">
        <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5" aria-label="Konum" data-i5="breadcrumb">
          <a href="{{ route('index') }}">Anasayfa</a>
          <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
          <span>Tüm Ürünler</span>
        </nav>
        <h1 class="font-heading text-page-title font-semibold leading-[1.12] tracking-[-0.02em] text-ink normal-case" data-i5="shop__title">Tüm Ürünler</h1>
      </div>

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
          <a href="{{ route('shops', array_filter(['siralama' => request('siralama')])) }}"
             class="{{ $filterClass }}{{ request('kategori') ? '' : $filterActiveClass }}"
             role="tab" aria-selected="{{ request('kategori') ? 'false' : 'true' }}" data-i5="filter">Tümü</a>
          @foreach ($categories as $category)
            <a href="{{ route('shops', array_filter(['kategori' => $category->slug, 'siralama' => request('siralama')])) }}"
               class="{{ $filterClass }}{{ request('kategori') === $category->slug ? $filterActiveClass : '' }}"
               role="tab" aria-selected="{{ request('kategori') === $category->slug ? 'true' : 'false' }}" data-i5="filter">{{ $category->name }}</a>
          @endforeach
        </div>

        <form method="get" action="{{ route('shops') }}" class="flex items-center gap-4 shrink-0 max-[899px]:w-full max-[899px]:justify-between max-[899px]:flex-col max-[899px]:items-stretch max-[899px]:gap-2.5" data-i5="shop__meta">
          @if (request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
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
          @php
            $mainImage = $product->images->first();
            $altImage = $product->images->skip(1)->first();
            $placeholder = asset('user/assets/foto5.jpeg');
          @endphp
          <article data-i5="reveal" data-i5-tags="product reveal" class="relative opacity-0 translate-y-6 transition-all duration-700 group/card [&.is-revealed]:opacity-100 [&.is-revealed]:translate-y-0" data-price="{{ (int) $product->price }}" data-category="{{ $product->category?->slug }}">
            <div class="relative aspect-square border-[3px] border-ink bg-surface mb-0 overflow-hidden shadow-brutal-sm transition-shadow duration-200 group-hover/card:shadow-brutal w-full" data-i5="product__media">
              <a href="{{ route('shopDetail', $product->slug) }}" class="block absolute inset-0 z-[1]">
                @if ($product->introduction_status)
                  <span class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-badge text-badge-fg font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-action/15 leading-none" data-i5="product__badge">Yeni</span>
                @elseif ($product->featured_status)
                  <span class="absolute top-[10px] left-[10px] z-[2] px-2.5 py-1.5 bg-accent text-on-dark font-body text-[11px] font-semibold tracking-[0.03em] normal-case border border-ink/15 leading-none" data-i5="product__badge">Öne Çıkan</span>
                @endif
                <img data-i5="product__img--main" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out {{ $altImage ? 'group-hover/card:opacity-0' : '' }}"
                     src="{{ $mainImage?->url ?? $placeholder }}" alt="{{ $product->title }}">
                @if ($altImage)
                  <img data-i5="product__img--alt" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-out opacity-0 group-hover/card:opacity-100"
                       src="{{ $altImage->url }}" alt="">
                @endif
              </a>
              @if ($product->stock_count > 0)
                @php
                  $canAddToCart = auth()->check()
                      && auth()->user()->type === \App\Enums\UserType::USER
                      && auth()->user()->status === \App\Enums\Status::ACTIVE;
                @endphp
                @if ($canAddToCart)
                  <form method="post" action="{{ route('cartStore') }}" class="absolute bottom-0 inset-x-0 z-[3]">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</button>
                  </form>
                @else
                  <a href="{{ route('loginPage') }}" class="absolute bottom-0 inset-x-0 z-[3] block px-3 py-[11px] bg-action text-on-dark font-body text-xs font-semibold normal-case tracking-normal text-center border-t-2 border-action/25 opacity-0 invisible translate-y-2 transition-all duration-200 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:translate-y-0 hover:bg-action-hover" data-i5="product__add">Sepete Ekle</a>
                @endif
              @endif
            </div>
            <a href="{{ route('shopDetail', $product->slug) }}" class="block">
              <h3 class="font-heading text-card-title font-semibold leading-snug normal-case text-ink m-0 px-4 pt-3.5" data-i5="product__name">{{ $product->title }}</h3>
              <p class="font-body text-sm font-medium leading-snug text-ink m-0 px-4 pb-4 pt-1" data-i5="product__price">
                @if ($product->stock_count === 0)
                  <span class="text-muted">Stokta yok</span>
                @else
                  {{ number_format((float) $product->price, 0, ',', '.') }}₺
                @endif
              </p>
            </a>
          </article>
        @empty
          <div class="col-span-full border-[3px] border-ink bg-surface px-6 py-14 text-center shadow-brutal-sm">
            <p class="font-heading text-section-title font-semibold text-ink">Ürün bulunamadı</p>
            <p class="mt-2 font-body text-[15px] text-muted">Bu kategoride henüz ürün yok veya filtreyi değiştirmeyi deneyin.</p>
            <a href="{{ route('shops') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover">Tüm Ürünleri Gör</a>
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
