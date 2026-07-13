@php
  $url = route('shops', ['kategori' => $category->slug]);
  $inFlyout = $inFlyout ?? false;
  $topLinkClass = 'block py-2 font-body text-[15px] font-medium text-muted normal-case tracking-normal transition-colors hover:text-accent';
  $flyoutLinkClass = 'block px-4 py-2.5 font-body text-[14px] font-medium text-muted normal-case transition-colors hover:text-accent hover:bg-hover';
@endphp

@if ($category->hasNestedChildren())
<div class="relative group/subfly {{ $inFlyout ? '' : 'min-w-0' }}" data-i5="mega-branch">
  <div class="flex items-stretch gap-0 {{ $inFlyout ? '' : 'pr-1' }}">
    <a href="{{ $url }}" class="{{ $inFlyout ? $flyoutLinkClass : $topLinkClass }} flex-1 min-w-0">{{ $category->name }}</a>
    <span class="flex items-center shrink-0 text-muted transition-colors group-hover/subfly:text-accent {{ $inFlyout ? 'px-2' : 'py-2 pl-0 pr-0.5' }}" aria-hidden="true" title="Alt kategoriler">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
    </span>
  </div>
  <div @class([
    'absolute z-[90] min-w-[220px] max-w-[280px] py-2 bg-surface border-[3px] border-ink shadow-brutal-sm opacity-0 invisible transition-all duration-150 pointer-events-none group-hover/subfly:opacity-100 group-hover/subfly:visible group-hover/subfly:pointer-events-auto',
    'left-full top-0 -translate-x-1 group-hover/subfly:translate-x-0 before:absolute before:-left-3 before:top-0 before:h-full before:w-3 before:content-[\'\']' => ! $inFlyout,
    'left-0 top-full mt-0 before:absolute before:-top-2 before:left-0 before:w-full before:h-2 before:content-[\'\']' => $inFlyout,
  ]) data-i5="mega-flyout">
    @foreach ($category->children as $child)
      @include('user.partials.nav-desktop-mega-branch', ['category' => $child, 'inFlyout' => true])
    @endforeach
  </div>
</div>
@else
<a href="{{ $url }}" class="{{ $inFlyout ? $flyoutLinkClass : $topLinkClass }}">{{ $category->name }}</a>
@endif
