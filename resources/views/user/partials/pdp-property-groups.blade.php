{{-- Beklenen: $propertyGroups, $defaultPropertySelections, $layout (stack|slider) --}}
@php
  use App\Enums\ProductPropertyGroupType;
  $layout = $layout ?? 'stack';
@endphp

@if ($propertyGroups->isNotEmpty())
  <div class="w-full min-w-0 max-w-full" data-pdp-properties data-pdp-properties-layout="{{ $layout }}">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
      <h2 class="m-0 font-heading text-[18px] font-semibold text-ink normal-case">Ürün Seçenekleri</h2>
      @if ($layout === 'slider' && $propertyGroups->count() > 1)
        <div class="hidden min-[960px]:flex items-center gap-2" data-pdp-band-nav>
          <button type="button"
                  class="flex h-10 w-10 items-center justify-center border-[3px] border-ink bg-surface shadow-brutal-sm transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal disabled:cursor-not-allowed disabled:opacity-40"
                  data-pdp-band-prev aria-label="Önceki seçenek grubu">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <button type="button"
                  class="flex h-10 w-10 items-center justify-center border-[3px] border-ink bg-surface shadow-brutal-sm transition-[transform,box-shadow] hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-brutal disabled:cursor-not-allowed disabled:opacity-40"
                  data-pdp-band-next aria-label="Sonraki seçenek grubu">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
          </button>
        </div>
      @endif
    </div>

    <div class="relative min-w-0" data-pdp-band-slider>
      <div class="pointer-events-none absolute inset-y-0 left-0 z-[1] hidden w-10 bg-gradient-to-r from-bg to-transparent min-[960px]:block" data-pdp-band-fade-left aria-hidden="true"></div>
      <div class="pointer-events-none absolute inset-y-0 right-0 z-[1] hidden w-10 bg-gradient-to-l from-bg to-transparent min-[960px]:block" data-pdp-band-fade-right aria-hidden="true"></div>

      <div @class([
        'gap-4',
        'grid grid-cols-1' => $layout === 'stack',
        'grid grid-cols-1 max-[959px]:grid-cols-1 min-[960px]:flex min-[960px]:snap-x min-[960px]:snap-mandatory min-[960px]:overflow-x-auto min-[960px]:scroll-smooth min-[960px]:pb-1 min-[960px]:[scrollbar-width:none] min-[960px]:[-ms-overflow-style:none] min-[960px]:[&::-webkit-scrollbar]:hidden' => $layout === 'slider',
      ]) data-pdp-band-track tabindex="{{ $layout === 'slider' ? '0' : '-1' }}" aria-label="Ürün seçenekleri">
        @foreach ($propertyGroups as $group)
          @php
            $selected = old('properties.'.$group->id, $defaultPropertySelections[$group->id] ?? []);
            if (! is_array($selected)) {
                $selected = $selected !== null && $selected !== '' ? [(int) $selected] : [];
            }
            $selected = array_map('intval', $selected);
            $hasDefault = $group->items->contains(fn ($item) => (bool) $item->is_default);
            $showNoneOption = $group->type === ProductPropertyGroupType::SINGLE
                && ! $hasDefault
                && $group->items->isNotEmpty();
            $noneChecked = $showNoneOption && $selected === [];
          @endphp
          <fieldset @class([
            'border-[3px] border-ink bg-bg p-4 shadow-brutal-sm min-w-0',
            'w-full' => $layout === 'stack',
            'w-full max-[959px]:w-full min-[960px]:w-[min(100%,320px)] min-[960px]:shrink-0 min-[960px]:snap-start lg:min-w-[260px] lg:w-[calc(25%-12px)]' => $layout === 'slider',
          ]) data-property-group data-group-type="{{ $group->type->value }}" data-group-title="{{ $group->title }}" @if($group->is_required && $group->type === \App\Enums\ProductPropertyGroupType::MULTIPLE) data-required-multiple="1" @endif>
            <legend class="px-2 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-ink">
              {{ $group->title }}
              @if ($group->is_required)
                <span class="text-announce">*</span>
              @endif
            </legend>
            <div class="mt-2 grid max-h-[280px] gap-2 overflow-y-auto pr-1 [scrollbar-width:thin]">
              @if ($showNoneOption)
                @php $noneInputId = 'prop-'.$group->id.'-none'; @endphp
                <label for="{{ $noneInputId }}" class="flex items-center justify-between gap-3 border-2 border-ink/15 bg-surface px-3 py-2.5 cursor-pointer hover:border-ink has-[:checked]:border-ink has-[:checked]:bg-hover">
                  <span class="inline-flex min-w-0 items-center gap-2.5">
                    <input
                      type="radio"
                      id="{{ $noneInputId }}"
                      name="properties[{{ $group->id }}]"
                      value=""
                      @checked($noneChecked)
                      class="accent-accent shrink-0"
                    >
                    <span class="truncate font-body text-[13px] font-semibold text-muted">Seçim Yok</span>
                  </span>
                </label>
              @endif
              @foreach ($group->items as $item)
                @php
                  $inputId = 'prop-'.$group->id.'-'.$item->id;
                  $isChecked = in_array((int) $item->id, $selected, true);
                @endphp
                <label for="{{ $inputId }}" class="flex items-center justify-between gap-3 border-2 border-ink/15 bg-surface px-3 py-2.5 cursor-pointer hover:border-ink has-[:checked]:border-ink has-[:checked]:bg-hover">
                  <span class="inline-flex min-w-0 items-center gap-2.5">
                    @if ($group->type === \App\Enums\ProductPropertyGroupType::SINGLE)
                      <input
                        type="radio"
                        id="{{ $inputId }}"
                        name="properties[{{ $group->id }}]"
                        value="{{ $item->id }}"
                        data-property-price="{{ (float) $item->price }}"
                        data-property-title="{{ $item->title }}"
                        @checked($isChecked)
                        @required($group->is_required && ! $showNoneOption)
                        class="accent-accent shrink-0"
                      >
                    @else
                      <input
                        type="checkbox"
                        id="{{ $inputId }}"
                        name="properties[{{ $group->id }}][]"
                        value="{{ $item->id }}"
                        data-property-price="{{ (float) $item->price }}"
                        data-property-title="{{ $item->title }}"
                        @checked($isChecked)
                        class="accent-accent shrink-0"
                      >
                    @endif
                    <span class="truncate font-body text-[13px] font-semibold text-ink">{{ $item->title }}</span>
                  </span>
                  <span class="whitespace-nowrap font-body text-[12px] font-bold text-muted">
                    @if ((float) $item->price > 0)
                      +{{ number_format((float) $item->price, 0, ',', '.') }}₺
                    @else
                      +0₺
                    @endif
                  </span>
                </label>
              @endforeach
            </div>
            @error('properties.'.$group->id)
              <p class="mt-2 text-[12px] font-semibold text-announce">{{ $message }}</p>
            @enderror
          </fieldset>
        @endforeach
      </div>
    </div>

    @error('properties')
      <p class="mt-3 text-[12px] font-semibold text-announce">{{ $message }}</p>
    @enderror
  </div>
@endif
