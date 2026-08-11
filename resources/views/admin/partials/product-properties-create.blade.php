{{-- Ürün ekleme: özellik grupları ürün formu ile birlikte gönderilir --}}
@php
  use App\Enums\ProductPropertyGroupType;
  $propertyGroupTemplates = $propertyGroupTemplates ?? collect();
  $oldGroups = old('property_groups', []);
  if (! is_array($oldGroups) || $oldGroups === []) {
      $oldGroups = [[
          'title' => '',
          'type' => ProductPropertyGroupType::SINGLE->value,
          'is_required' => false,
          'sort_order' => 0,
          'paste' => '',
          'items' => [
              ['title' => '', 'price' => 0, 'is_default' => false],
              ['title' => '', 'price' => 0, 'is_default' => false],
              ['title' => '', 'price' => 0, 'is_default' => false],
          ],
      ]];
  }
@endphp

<section class="overflow-hidden rounded-xl bg-surface shadow-card" data-create-property-manager>
  <div class="border-b border-ink/10 px-5 py-4 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h3 class="font-heading text-[16px] font-bold text-ink">Ürün Özellikleri</h3>
      <p class="mt-1 font-body text-[12px] text-muted">Ölçü, malzeme vb. grupları ürünle birlikte tek seferde ekleyebilirsiniz. Boş gruplar yok sayılır.</p>
    </div>
    <button type="button" data-create-add-group class="rounded-lg border border-ink/15 bg-cream px-3 py-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-ink hover:bg-hover">
      + Grup Ekle
    </button>
  </div>

  <div class="p-5 grid gap-5">
    @if ($propertyGroupTemplates->isNotEmpty())
      <div>
        <label class="mb-1 block font-body text-[12px] font-bold text-muted">Şablondan kopyala (opsiyonel)</label>
        <select data-create-template-select class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
          <option value="">— Şablon seçilmedi —</option>
          @foreach ($propertyGroupTemplates as $template)
            <option value="{{ $template['id'] }}">
              #{{ $template['id'] }} — {{ $template['title'] }}
              ({{ $template['items_count'] }} seçenek{{ $template['product_code'] ? ' · '.$template['product_code'] : '' }})
            </option>
          @endforeach
        </select>
        <p class="mt-1 font-body text-[11px] text-muted">Seçince yeni bir grup kartına kopyalanır; ürün kaydıyla birlikte eklenir.</p>
      </div>
    @endif

    <div class="grid gap-4" data-create-groups>
      @foreach ($oldGroups as $gIndex => $group)
        @include('admin.partials.product-property-group-create-card', [
          'gIndex' => $gIndex,
          'group' => $group,
        ])
      @endforeach
    </div>

    @error('property_groups') <p class="font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
    @error('property_groups.*') <p class="font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
  </div>
</section>

<script type="application/json" id="create-property-templates-json">@json($propertyGroupTemplates ?? [])</script>

<template id="create-property-group-template">
  @include('admin.partials.product-property-group-create-card', [
    'gIndex' => '__G__',
    'group' => [
      'title' => '',
      'type' => ProductPropertyGroupType::SINGLE->value,
      'is_required' => false,
      'sort_order' => 0,
      'paste' => '',
      'items' => [
        ['title' => '', 'price' => 0, 'is_default' => false],
        ['title' => '', 'price' => 0, 'is_default' => false],
        ['title' => '', 'price' => 0, 'is_default' => false],
      ],
    ],
  ])
</template>

<template id="create-property-item-row-template">
  <tr class="[&_td]:px-3 [&_td]:py-2" data-create-item-row>
    <td>
      <input type="text" name="property_groups[__G__][items][__I__][title]" maxlength="120" placeholder="Örn. 100x100"
             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
    </td>
    <td>
      <input type="number" name="property_groups[__G__][items][__I__][price]" step="0.01" min="0" value="0"
             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
    </td>
    <td>
      <label class="inline-flex items-center gap-1.5 cursor-pointer">
        <input type="hidden" name="property_groups[__G__][items][__I__][is_default]" value="0">
        <input type="checkbox" name="property_groups[__G__][items][__I__][is_default]" value="1" class="h-4 w-4 accent-accent">
        <span class="font-body text-[11px] text-muted">Varsayılan</span>
      </label>
    </td>
    <td class="text-right">
      <button type="button" data-create-remove-item class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
    </td>
  </tr>
</template>
