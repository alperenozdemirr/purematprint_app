@php
  $shippingBadge = $siteSetting->shippingTrustBadge();
  $mode = $mode ?? 'grid';
  $items = [
      ['title' => $siteSetting->deliveryTimeLabel(), 'subtitle' => 'Teslimat'],
      ['title' => $shippingBadge['title'], 'subtitle' => $shippingBadge['subtitle']],
      ['title' => 'Prova', 'subtitle' => 'Dijital Onay'],
  ];
@endphp

@if ($mode === 'band-cards')
  @foreach ($items as $item)
    <article class="w-full max-[959px]:w-full min-[960px]:w-[min(100%,220px)] min-[960px]:shrink-0 min-[960px]:snap-start border-[3px] border-ink bg-bg p-4 shadow-brutal-sm text-center" data-i5="pdp-trust__item">
      <strong class="block font-body text-[11px] font-bold uppercase mb-1">{{ $item['title'] }}</strong>
      <span class="text-[11px] text-muted">{{ $item['subtitle'] }}</span>
    </article>
  @endforeach
@else
  <div class="grid grid-cols-3 gap-3 max-[599px]:grid-cols-1 {{ $class ?? 'mb-0' }}" data-i5="pdp-trust">
    @foreach ($items as $item)
      <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
        <strong class="block font-body text-[11px] font-bold uppercase mb-1">{{ $item['title'] }}</strong>
        <span class="text-[11px] text-muted">{{ $item['subtitle'] }}</span>
      </div>
    @endforeach
  </div>
@endif
