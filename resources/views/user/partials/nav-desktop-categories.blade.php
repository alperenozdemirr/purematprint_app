@php
  $navLinkClass = 'relative inline-flex items-center font-body text-[13px] font-bold tracking-wider uppercase text-on-dark whitespace-nowrap bg-transparent border-0 p-0 cursor-pointer transition-colors hover:text-white min-[1040px]:h-full min-[1040px]:px-1.5 after:absolute after:left-0 after:bottom-[-5px] min-[1040px]:after:bottom-3 after:h-0.5 after:w-full after:bg-white after:origin-left after:scale-x-0 hover:after:scale-x-100 after:transition-transform [&.is-active]:text-white [&.is-active]:after:scale-x-100';
  $megaPanelClass = 'absolute inset-x-0 top-[calc(100%+3px)] w-full bg-surface border-b-[3px] border-ink opacity-0 invisible translate-y-1 transition-all duration-200 pointer-events-none z-[80] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:pointer-events-auto min-[1040px]:translate-y-0 group-[.is-open]/mega:opacity-100 group-[.is-open]/mega:visible group-[.is-open]/mega:translate-y-0 group-[.is-open]/mega:pointer-events-auto';
@endphp

@foreach ($menuCategories as $category)
  @php
    $categoryUrl = route('shops', ['kategori' => $category->slug]);
    $childCount = $category->children->count();
    $gridClass = match (true) {
        $childCount <= 1 => 'grid-cols-1 min-[1200px]:grid-cols-1',
        $childCount === 2 => 'grid-cols-2 min-[1200px]:grid-cols-2',
        $childCount === 3 => 'grid-cols-2 min-[1200px]:grid-cols-3',
        default => 'grid-cols-2 min-[1200px]:grid-cols-4',
    };
  @endphp

  @if ($category->hasNestedChildren())
  <li class="static min-[1040px]:flex min-[1040px]:items-center group/mega before:content-[''] before:absolute before:inset-x-0 before:bottom-full before:h-3" data-i5="mega-item">
    <a href="{{ $categoryUrl }}" class="{{ $navLinkClass }}" data-i5="mega-nav__link">{{ $category->name }}</a>
    <div class="{{ $megaPanelClass }}" data-i5="mega-panel">
      <div class="mx-auto max-w-[1440px] px-12 py-9 pb-11">
        <div class="pmp-mega-grid grid {{ $gridClass }} gap-x-12 gap-y-1 min-[1200px]:gap-x-14 min-[1200px]:gap-y-1.5">
          <a class="block py-2 col-span-full mb-2 pb-3.5 border-b border-ink/10 font-body text-xs font-bold uppercase tracking-wider text-ink hover:text-accent" href="{{ $categoryUrl }}">Tümünü Gör</a>
          @foreach ($category->children as $child)
            @include('user.partials.nav-desktop-mega-branch', ['category' => $child, 'inFlyout' => false])
          @endforeach
        </div>
      </div>
    </div>
  </li>
  @else
  <li>
    <a href="{{ $categoryUrl }}" class="{{ $navLinkClass }}" data-i5="mega-nav__link">{{ $category->name }}</a>
  </li>
  @endif
@endforeach
