@php
  use App\Enums\OrderDesignType;
  use App\Support\CheckoutDesignPreference;

  $selectedDesignType = old('design_type', $pdpSelectedDesignType ?? CheckoutDesignPreference::get());
@endphp

<div class="w-full min-w-0 max-w-full" data-pdp-design-preference>
  <div class="mb-4">
    <h2 class="m-0 font-heading text-[18px] font-semibold text-ink normal-case">Tasarım Tercihi</h2>
    <p class="mt-2 mb-0 text-xs text-muted leading-relaxed">
      Baskı dosyalarınız ödeme aşamasında istenecektir.
    </p>
  </div>
  <div class="grid gap-3">
    @foreach (OrderDesignType::checkoutOptions() as $designType)
      <label class="flex items-start gap-3 border-[3px] border-ink bg-bg p-4 shadow-brutal-sm cursor-pointer hover:border-ink has-[:checked]:border-accent has-[:checked]:bg-hover">
        <input
          type="radio"
          name="design_type"
          value="{{ $designType->value }}"
          class="mt-1 accent-accent shrink-0"
          @checked($selectedDesignType === $designType->value)
          required
          data-pdp-design-type
        >
        <span class="font-body text-[13px] font-semibold text-ink">{{ $designType->label() }}</span>
      </label>
    @endforeach
  </div>
  @error('design_type')
    <p class="mt-2 text-[12px] font-semibold text-announce">{{ $message }}</p>
  @enderror
</div>
