@props([
    'class' => 'h-7 w-auto',
    'invertOnDark' => false,
])
<img src="{{ $siteLogoUrl }}" alt="PureMatPrint" {{ $attributes->merge(['class' => trim($class.' '.($siteLogoIsCustom ? '' : ($invertOnDark ? 'brightness-0 invert' : '')))]) }}>
