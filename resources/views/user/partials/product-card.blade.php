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
