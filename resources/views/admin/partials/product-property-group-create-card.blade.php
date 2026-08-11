@php
  use App\Enums\ProductPropertyGroupType;
  $gIndex = $gIndex ?? 0;
  $group = is_array($group ?? null) ? $group : [];
  $items = is_array($group['items'] ?? null) ? $group['items'] : [['title' => '', 'price' => 0, 'is_default' => false]];
  if ($items === []) {
      $items = [['title' => '', 'price' => 0, 'is_default' => false]];
  }
@endphp

<details class="group rounded-xl border border-ink/10 bg-cream/40" open data-create-group>
  <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 [&::-webkit-details-marker]:hidden">
    <div class="min-w-0">
      <p class="m-0 truncate font-heading text-[15px] font-bold text-ink" data-create-group-summary-title>
        {{ filled($group['title'] ?? null) ? $group['title'] : 'Yeni özellik grubu' }}
      </p>
      <p class="m-0 mt-0.5 font-body text-[12px] text-muted">Grup bilgisi ve seçenekler</p>
    </div>
    <span class="inline-flex shrink-0 items-center gap-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted">
      <span class="group-open:hidden">Aç</span>
      <span class="hidden group-open:inline">Kapat</span>
      <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
    </span>
  </summary>

  <div class="grid gap-4 border-t border-ink/10 p-4">
    <div class="flex justify-end">
      <button type="button" data-create-remove-group class="font-body text-[12px] font-bold text-danger hover:underline">Grubu Kaldır</button>
    </div>

    <div class="grid gap-3 md:grid-cols-[1fr_160px_120px_100px] md:items-end">
      <div>
        <label class="mb-1 block font-body text-[12px] font-bold text-muted">Grup adı</label>
        <input type="text" name="property_groups[{{ $gIndex }}][title]" maxlength="120" placeholder="Örn. Ölçü"
               value="{{ $group['title'] ?? '' }}" data-create-group-title
               class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
      </div>
      <div>
        <label class="mb-1 block font-body text-[12px] font-bold text-muted">Seçim tipi</label>
        <select name="property_groups[{{ $gIndex }}][type]" class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
          @foreach (ProductPropertyGroupType::cases() as $type)
            <option value="{{ $type->value }}" @selected(($group['type'] ?? ProductPropertyGroupType::SINGLE->value) === $type->value)>{{ $type->label() }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="mb-1 block font-body text-[12px] font-bold text-muted">Sıra</label>
        <input type="number" name="property_groups[{{ $gIndex }}][sort_order]" min="0" value="{{ $group['sort_order'] ?? $gIndex }}"
               class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
      </div>
      <label class="flex items-center gap-2 pb-2 cursor-pointer">
        <input type="hidden" name="property_groups[{{ $gIndex }}][is_required]" value="0">
        <input type="checkbox" name="property_groups[{{ $gIndex }}][is_required]" value="1" class="h-4 w-4 accent-accent" @checked(!empty($group['is_required']))>
        <span class="font-body text-[12px] font-bold text-ink">Zorunlu</span>
      </label>
    </div>

    <div class="rounded-lg border border-dashed border-ink/20 bg-surface p-4 grid gap-4">
      <div class="flex flex-wrap items-center justify-between gap-2">
        <h4 class="font-heading text-[14px] font-bold text-ink m-0">Seçenekler</h4>
        <button type="button" data-create-add-item class="rounded-lg border border-ink/15 bg-cream px-3 py-1.5 font-body text-[11px] font-bold uppercase text-ink hover:bg-hover">+ Satır Ekle</button>
      </div>

      <div class="overflow-x-auto rounded-lg border border-ink/10">
        <table class="w-full min-w-[560px] text-left">
          <thead>
            <tr class="bg-cream/70 [&_th]:px-3 [&_th]:py-2 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:text-muted">
              <th>Seçenek</th>
              <th>Ek Fiyat</th>
              <th>Varsayılan</th>
              <th></th>
            </tr>
          </thead>
          <tbody data-create-item-rows class="divide-y divide-ink/8">
            @foreach ($items as $iIndex => $item)
              <tr class="[&_td]:px-3 [&_td]:py-2" data-create-item-row>
                <td>
                  <input type="text" name="property_groups[{{ $gIndex }}][items][{{ $iIndex }}][title]" maxlength="120" placeholder="Örn. 100x100"
                         value="{{ $item['title'] ?? '' }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                </td>
                <td>
                  <input type="number" name="property_groups[{{ $gIndex }}][items][{{ $iIndex }}][price]" step="0.01" min="0" value="{{ $item['price'] ?? 0 }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                </td>
                <td>
                  <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="hidden" name="property_groups[{{ $gIndex }}][items][{{ $iIndex }}][is_default]" value="0">
                    <input type="checkbox" name="property_groups[{{ $gIndex }}][items][{{ $iIndex }}][is_default]" value="1" class="h-4 w-4 accent-accent" @checked(!empty($item['is_default']))>
                    <span class="font-body text-[11px] text-muted">Varsayılan</span>
                  </label>
                </td>
                <td class="text-right">
                  <button type="button" data-create-remove-item class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div>
        <label class="mb-1 block font-body text-[12px] font-bold text-muted">Hızlı yapıştır (opsiyonel)</label>
        <textarea name="property_groups[{{ $gIndex }}][paste]" rows="3" placeholder="50x50|0&#10;100x100|200&#10;150x150|350"
                  class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[13px] text-ink outline-none focus:border-accent">{{ $group['paste'] ?? '' }}</textarea>
        <p class="mt-1 font-body text-[11px] text-muted">Format: <strong>Başlık|Fiyat</strong></p>
      </div>
    </div>
  </div>
</details>
