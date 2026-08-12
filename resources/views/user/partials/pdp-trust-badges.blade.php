@php
  $shippingBadge = $siteSetting->shippingTrustBadge();
@endphp
<div class="grid grid-cols-3 gap-3 max-[599px]:grid-cols-1 {{ $class ?? 'mb-0' }}" data-i5="pdp-trust">
  <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
    <strong class="block font-body text-[11px] font-bold uppercase mb-1">{{ $siteSetting->deliveryTimeLabel() }}</strong>
    <span class="text-[11px] text-muted">Teslimat</span>
  </div>
  <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
    <strong class="block font-body text-[11px] font-bold uppercase mb-1">{{ $shippingBadge['title'] }}</strong>
    <span class="text-[11px] text-muted">{{ $shippingBadge['subtitle'] }}</span>
  </div>
  <div class="p-3 border-[3px] border-ink bg-bg text-center" data-i5="pdp-trust__item">
    <strong class="block font-body text-[11px] font-bold uppercase mb-1">Prova</strong>
    <span class="text-[11px] text-muted">Dijital Onay</span>
  </div>
</div>
