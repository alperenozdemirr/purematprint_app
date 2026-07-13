@php
  $url = route('shops', ['kategori' => $category->slug]);
  $depth = $depth ?? 1;
  $isDeep = $depth >= 2;
  $paddingLeft = $isDeep ? 20 : 32;
  $fontSize = $depth === 1 ? '15px' : ($depth === 2 ? '14px' : '13px');
  $linkClass = 'block font-body font-medium normal-case text-ink/90 bg-bg hover:bg-hover transition-colors border-b border-ink/50';
  $isToggleLevel = $depth === 1 && $category->hasNestedChildren();
  $displayName = $isDeep ? '− '.$category->name : $category->name;
@endphp

@if ($category->hasNestedChildren())
<details @class([
  'mobile-nav-sub border-b border-ink/50 bg-bg',
  'mobile-nav-sub--toggle' => $isToggleLevel,
  'mobile-nav-sub--deep' => ! $isToggleLevel,
])>
  <summary style="padding: 10px 20px 10px {{ $paddingLeft }}px; font-size: {{ $fontSize }};">
    {{ $displayName }}
  </summary>
  <div class="bg-bg pb-1">
    <a href="{{ $url }}" class="block py-2.5 px-5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-accent hover:text-ink transition-colors border-b border-ink/50">Tümünü Gör</a>
    @foreach ($category->children as $child)
      @include('user.partials.nav-mobile-category-item', ['category' => $child, 'depth' => $depth + 1])
    @endforeach
  </div>
</details>
@else
<a href="{{ $url }}" class="{{ $linkClass }}" style="padding: 10px 20px 10px {{ $paddingLeft }}px; font-size: {{ $fontSize }};">{{ $displayName }}</a>
@endif
