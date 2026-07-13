@php
  $detailsClass = '[&>summary]:flex [&>summary]:items-center [&>summary]:justify-between [&>summary]:gap-3 [&>summary]:font-body [&>summary]:text-[20px] [&>summary]:font-bold [&>summary]:uppercase [&>summary]:[padding:16px_20px] [&>summary]:border-b-2 [&>summary]:border-ink [&>summary]:cursor-pointer [&>summary]:list-none [&>summary]:bg-surface hover:[&>summary]:bg-hover [&>summary::-webkit-details-marker]:hidden [&>summary::marker]:content-none [&_a]:block [&_a]:font-body [&_a]:text-[15px] [&_a]:font-medium [&_a]:normal-case [&_a]:[padding:12px_20px_12px_32px] [&_a]:border-b [&_a]:border-ink [&_a]:bg-bg hover:[&_a]:bg-hover bg-surface';
  $simpleLinkClass = 'block font-body text-[20px] font-bold uppercase [padding:16px_20px] border-b-2 border-ink text-ink bg-surface hover:bg-hover [&.is-current]:bg-hover [&.is-current]:text-accent';
@endphp

@once
<style>
  .mobile-nav-sub > summary {
    display: flex;
    align-items: center;
    cursor: pointer;
    list-style: none;
    font-family: 'IBM Plex Sans', system-ui, sans-serif;
    font-weight: 600;
    font-style: normal;
    text-transform: none;
    color: #1a1a1a;
    background: #fffdf8;
  }
  .mobile-nav-sub > summary:hover { background: #f2ece3; }
  .mobile-nav-sub > summary::-webkit-details-marker { display: none !important; }
  .mobile-nav-sub > summary::marker { content: none !important; display: none !important; }

  .mobile-nav-sub--toggle > summary { justify-content: space-between; gap: 12px; }
  .mobile-nav-sub--toggle > summary::after {
    content: '+';
    flex-shrink: 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1;
    color: #5e5a54;
  }
  .mobile-nav-sub--toggle[open] > summary::after { content: '−'; }

  .mobile-nav-sub--deep > summary::after { content: none !important; }
</style>
@endonce

@foreach ($menuCategories as $category)
  @php $categoryUrl = route('shops', ['kategori' => $category->slug]); @endphp

  @if ($category->hasNestedChildren())
  <details class="{{ $detailsClass }}">
    <summary>{{ $category->name }}</summary>
    <a href="{{ $categoryUrl }}">Tümünü Gör</a>
    @foreach ($category->children as $child)
      @include('user.partials.nav-mobile-category-item', ['category' => $child, 'depth' => 1])
    @endforeach
  </details>
  @else
  <a href="{{ $categoryUrl }}" class="{{ $simpleLinkClass }}" data-i5="mobile__link">{{ $category->name }}</a>
  @endif
@endforeach
