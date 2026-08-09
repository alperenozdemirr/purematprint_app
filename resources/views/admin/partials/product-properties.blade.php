{{-- Beklenen: $product (propertyGroups.items yüklü), opsiyonel $propertyGroupTemplates --}}
@php
  use App\Enums\ProductPropertyGroupType;
  $propertyGroups = $product->propertyGroups;
  $propertyGroupTemplates = $propertyGroupTemplates ?? collect();
@endphp

<section class="overflow-hidden rounded-xl bg-surface shadow-card" data-property-manager>
  <div class="border-b border-ink/10 px-5 py-4 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h3 class="font-heading text-[16px] font-bold text-ink">Ürün Özellikleri</h3>
      <p class="mt-1 font-body text-[12px] text-muted">Ölçü, malzeme, ek hizmet gibi seçenekleri buradan yönetin. Seçenekleri toplu ekleyebilirsiniz.</p>
    </div>
  </div>

  <div class="p-5 grid gap-6">
    @if (session('success'))
      <div class="rounded-lg border border-accent/30 bg-accent/5 px-3.5 py-2.5 font-body text-[13px] font-semibold text-ink">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="rounded-lg border border-danger/30 bg-danger/5 px-3.5 py-2.5 font-body text-[13px] font-semibold text-danger">{{ session('error') }}</div>
    @endif
    @error('items')
      <div class="rounded-lg border border-danger/30 bg-danger/5 px-3.5 py-2.5 font-body text-[13px] font-semibold text-danger">{{ $message }}</div>
    @enderror

    @forelse ($propertyGroups as $group)
      <details class="group rounded-xl border border-ink/10 bg-cream/40" id="property-group-{{ $group->id }}" @if($errors->any() && (string) old('_group_focus') === (string) $group->id) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 [&::-webkit-details-marker]:hidden">
          <div class="min-w-0">
            <p class="m-0 truncate font-heading text-[15px] font-bold text-ink">{{ $group->title }}</p>
            <p class="m-0 mt-0.5 font-body text-[12px] text-muted">
              #{{ $group->id }} · {{ $group->type->label() }}
              · {{ $group->items->count() }} seçenek
              @if ($group->is_required) · <span class="font-semibold text-danger">Zorunlu</span>@endif
            </p>
          </div>
          <span class="inline-flex shrink-0 items-center gap-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-muted">
            <span class="group-open:hidden">Aç</span>
            <span class="hidden group-open:inline">Kapat</span>
            <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
          </span>
        </summary>

        <div class="grid gap-4 border-t border-ink/10 p-4">
          <form action="{{ route('admin.productPropertyGroupUpdate', $group->id) }}" method="POST" class="grid gap-3 md:grid-cols-[1fr_160px_120px_100px_auto] md:items-end">
            @csrf
            <input type="hidden" name="_group_focus" value="{{ $group->id }}">
            <div>
              <label class="mb-1 block font-body text-[12px] font-bold text-muted">Grup adı</label>
              <input type="text" name="title" value="{{ old('title', $group->title) }}" required maxlength="120"
                     class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
            </div>
            <div>
              <label class="mb-1 block font-body text-[12px] font-bold text-muted">Seçim tipi</label>
              <select name="type" class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
                @foreach (ProductPropertyGroupType::cases() as $type)
                  <option value="{{ $type->value }}" @selected(old('type', $group->type->value) === $type->value)>{{ $type->label() }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="mb-1 block font-body text-[12px] font-bold text-muted">Sıra</label>
              <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $group->sort_order) }}"
                     class="w-full rounded-lg border border-ink/10 bg-surface px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
            </div>
            <label class="flex items-center gap-2 pb-2 cursor-pointer">
              <input type="hidden" name="is_required" value="0">
              <input type="checkbox" name="is_required" value="1" class="h-4 w-4 accent-accent" @checked(old('is_required', $group->is_required))>
              <span class="font-body text-[12px] font-bold text-ink">Zorunlu</span>
            </label>
            <div class="flex gap-2">
              <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-accent px-3 py-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-on-dark hover:bg-accent-dark">Kaydet</button>
            </div>
          </form>

          <div class="flex justify-end">
            <form action="{{ route('admin.productPropertyGroupDelete', $group->id) }}" method="POST" onsubmit="return confirm('Bu grubu ve tüm seçeneklerini silmek istediğinize emin misiniz?')">
              @csrf
              <button type="submit" class="font-body text-[12px] font-bold text-danger hover:underline">Grubu Sil</button>
            </form>
          </div>

          @if ($group->items->isNotEmpty())
            <form action="{{ route('admin.productPropertyItemBulkUpdate', $group->id) }}" method="POST" class="overflow-x-auto rounded-lg border border-ink/10 bg-surface">
              @csrf
              <table class="w-full min-w-[640px] text-left">
                <thead>
                  <tr class="bg-cream/70 [&_th]:px-3 [&_th]:py-2 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:text-muted">
                    <th>Seçenek</th>
                    <th>Ek Fiyat</th>
                    <th>Sıra</th>
                    <th>Varsayılan</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-ink/8">
                  @foreach ($group->items as $item)
                    <tr class="[&_td]:px-3 [&_td]:py-2 [&_td]:align-middle">
                      <td>
                        <input type="text" name="items[{{ $item->id }}][title]" value="{{ $item->title }}" required maxlength="120"
                               class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                      </td>
                      <td>
                        <input type="number" name="items[{{ $item->id }}][price]" step="0.01" min="0" value="{{ $item->price }}" required
                               class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                      </td>
                      <td>
                        <input type="number" name="items[{{ $item->id }}][sort_order]" min="0" value="{{ $item->sort_order }}"
                               class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                      </td>
                      <td>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                          <input type="hidden" name="items[{{ $item->id }}][is_default]" value="0">
                          <input type="checkbox" name="items[{{ $item->id }}][is_default]" value="1" class="h-4 w-4 accent-accent" @checked($item->is_default)>
                          <span class="font-body text-[11px] text-muted">Varsayılan</span>
                        </label>
                      </td>
                      <td class="text-right whitespace-nowrap">
                        <button type="submit" formaction="{{ route('admin.productPropertyItemDelete', $item->id) }}" formmethod="POST"
                                formnovalidate
                                onclick="return confirm('Bu seçeneği silmek istediğinize emin misiniz?')"
                                class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="flex justify-end border-t border-ink/10 px-3 py-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-ink px-4 py-2 font-body text-[11px] font-bold uppercase tracking-[0.04em] text-on-dark hover:opacity-90">
                  Seçenekleri Toplu Kaydet
                </button>
              </div>
            </form>
          @endif

          <div class="rounded-lg border border-dashed border-ink/20 bg-surface p-4 grid gap-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <h4 class="font-heading text-[14px] font-bold text-ink">Toplu seçenek ekle</h4>
              <button type="button" data-bulk-add-row class="rounded-lg border border-ink/15 bg-cream px-3 py-1.5 font-body text-[11px] font-bold uppercase text-ink hover:bg-hover">
                + Satır Ekle
              </button>
            </div>

            <form action="{{ route('admin.productPropertyItemBulkStore', $group->id) }}" method="POST" class="grid gap-4" data-bulk-form>
              @csrf

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
                  <tbody data-bulk-rows class="divide-y divide-ink/8">
                    @for ($i = 0; $i < 3; $i++)
                      <tr class="[&_td]:px-3 [&_td]:py-2" data-bulk-row>
                        <td>
                          <input type="text" name="items[{{ $i }}][title]" maxlength="120" placeholder="Örn. 100x100"
                                 class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </td>
                        <td>
                          <input type="number" name="items[{{ $i }}][price]" step="0.01" min="0" value="0"
                                 class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                        </td>
                        <td>
                          <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="hidden" name="items[{{ $i }}][is_default]" value="0">
                            <input type="checkbox" name="items[{{ $i }}][is_default]" value="1" class="h-4 w-4 accent-accent">
                            <span class="font-body text-[11px] text-muted">Varsayılan</span>
                          </label>
                        </td>
                        <td class="text-right">
                          <button type="button" data-bulk-remove-row class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
                        </td>
                      </tr>
                    @endfor
                  </tbody>
                </table>
              </div>

              <div>
                <label class="mb-1 block font-body text-[12px] font-bold text-muted">Hızlı yapıştır (opsiyonel)</label>
                <textarea name="paste" rows="4" placeholder="Her satıra bir seçenek yazın.&#10;Örnek:&#10;50x50|0&#10;100x100|200&#10;150x150|350"
                          class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[13px] text-ink outline-none focus:border-accent"></textarea>
                <p class="mt-1 font-body text-[11px] text-muted">Format: <strong>Başlık|Fiyat</strong> — ayırıcı olarak | , veya ; kullanabilirsiniz. Boş satırlar yok sayılır.</p>
              </div>

              <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-accent px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-on-dark hover:bg-accent-dark">
                  Toplu Ekle
                </button>
              </div>
            </form>
          </div>
        </div>
      </details>
    @empty
      <p class="font-body text-[13px] text-muted">Henüz özellik grubu yok. Aşağıdan ilk grubu ekleyebilirsiniz.</p>
    @endforelse

    <div class="rounded-xl border border-dashed border-ink/20 p-4 grid gap-4" data-new-group-panel>
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
          <h4 class="font-heading text-[14px] font-bold text-ink">Yeni özellik grubu + seçenekler</h4>
          <p class="mt-1 font-body text-[12px] text-muted">Grup adı ve seçenekleri tek seferde kaydedin. İsterseniz geçmiş bir gruptan kopyalayın.</p>
        </div>
      </div>

      @if (($propertyGroupTemplates ?? collect())->isNotEmpty())
        <div>
          <label class="mb-1 block font-body text-[12px] font-bold text-muted">Şablondan kopyala (opsiyonel)</label>
          <select data-template-select class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
            <option value="">— Şablon seçilmedi —</option>
            @foreach ($propertyGroupTemplates as $template)
              <option value="{{ $template['id'] }}">
                #{{ $template['id'] }} — {{ $template['title'] }}
                ({{ $template['items_count'] }} seçenek{{ $template['product_code'] ? ' · '.$template['product_code'] : '' }})
              </option>
            @endforeach
          </select>
          <p class="mt-1 font-body text-[11px] text-muted">Seçince grup adı ve seçenekler forma kopyalanır. Kaydettiğinizde bu ürüne yeni kayıt olarak eklenir.</p>
        </div>
      @endif

      <form action="{{ route('admin.productPropertyGroupWithItemsStore', $product->id) }}" method="POST" class="grid gap-4" data-new-group-form>
        @csrf

        <div class="grid gap-3 md:grid-cols-[1fr_160px_120px_100px] md:items-end">
          <div>
            <label class="mb-1 block font-body text-[12px] font-bold text-muted">Grup adı</label>
            <input type="text" name="title" required maxlength="120" placeholder="Örn. Ölçü" data-new-group-title
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
          </div>
          <div>
            <label class="mb-1 block font-body text-[12px] font-bold text-muted">Seçim tipi</label>
            <select name="type" data-new-group-type class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
              @foreach (ProductPropertyGroupType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="mb-1 block font-body text-[12px] font-bold text-muted">Sıra</label>
            <input type="number" name="sort_order" min="0" value="{{ ((int) $propertyGroups->max('sort_order')) + 1 }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[14px] text-ink outline-none focus:border-accent">
          </div>
          <label class="flex items-center gap-2 pb-2 cursor-pointer">
            <input type="hidden" name="is_required" value="0">
            <input type="checkbox" name="is_required" value="1" class="h-4 w-4 accent-accent" data-new-group-required>
            <span class="font-body text-[12px] font-bold text-ink">Zorunlu</span>
          </label>
        </div>

        <div class="rounded-lg border border-ink/10 bg-surface p-3 grid gap-3">
          <div class="flex flex-wrap items-center justify-between gap-2">
            <h5 class="font-body text-[12px] font-bold uppercase tracking-[0.04em] text-ink m-0">Seçenekler</h5>
            <button type="button" data-new-group-add-row class="rounded-lg border border-ink/15 bg-cream px-3 py-1.5 font-body text-[11px] font-bold uppercase text-ink hover:bg-hover">+ Satır Ekle</button>
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
              <tbody data-new-group-rows class="divide-y divide-ink/8">
                @for ($i = 0; $i < 3; $i++)
                  <tr class="[&_td]:px-3 [&_td]:py-2" data-bulk-row>
                    <td>
                      <input type="text" name="items[{{ $i }}][title]" maxlength="120" placeholder="Örn. 100x100"
                             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                    </td>
                    <td>
                      <input type="number" name="items[{{ $i }}][price]" step="0.01" min="0" value="0"
                             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
                    </td>
                    <td>
                      <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="hidden" name="items[{{ $i }}][is_default]" value="0">
                        <input type="checkbox" name="items[{{ $i }}][is_default]" value="1" class="h-4 w-4 accent-accent">
                        <span class="font-body text-[11px] text-muted">Varsayılan</span>
                      </label>
                    </td>
                    <td class="text-right">
                      <button type="button" data-bulk-remove-row class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
                    </td>
                  </tr>
                @endfor
              </tbody>
            </table>
          </div>

          <div>
            <label class="mb-1 block font-body text-[12px] font-bold text-muted">Hızlı yapıştır (opsiyonel)</label>
            <textarea name="paste" rows="3" data-new-group-paste placeholder="50x50|0&#10;100x100|200&#10;150x150|350"
                      class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2 font-body text-[13px] text-ink outline-none focus:border-accent"></textarea>
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-accent px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-on-dark hover:bg-accent-dark">
            Grup + Seçenekleri Kaydet
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<script type="application/json" id="property-group-templates-json">@json($propertyGroupTemplates ?? [])</script>

<template id="property-bulk-row-template">
  <tr class="[&_td]:px-3 [&_td]:py-2" data-bulk-row>
    <td>
      <input type="text" name="items[__INDEX__][title]" maxlength="120" placeholder="Örn. 100x100"
             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
    </td>
    <td>
      <input type="number" name="items[__INDEX__][price]" step="0.01" min="0" value="0"
             class="w-full rounded-lg border border-ink/10 bg-cream px-2.5 py-1.5 font-body text-[13px] text-ink outline-none focus:border-accent">
    </td>
    <td>
      <label class="inline-flex items-center gap-1.5 cursor-pointer">
        <input type="hidden" name="items[__INDEX__][is_default]" value="0">
        <input type="checkbox" name="items[__INDEX__][is_default]" value="1" class="h-4 w-4 accent-accent">
        <span class="font-body text-[11px] text-muted">Varsayılan</span>
      </label>
    </td>
    <td class="text-right">
      <button type="button" data-bulk-remove-row class="rounded-lg bg-cream px-2.5 py-1.5 font-body text-[10px] font-bold uppercase text-danger hover:bg-danger hover:text-on-dark">Sil</button>
    </td>
  </tr>
</template>

<script>
  (function () {
    const root = document.querySelector('[data-property-manager]');
    if (!root) return;

    const template = document.getElementById('property-bulk-row-template');
    if (!template) return;

    let templates = [];
    try {
      templates = JSON.parse(document.getElementById('property-group-templates-json')?.textContent || '[]');
    } catch (e) {
      templates = [];
    }

    const reindexRows = (tbody) => {
      tbody.querySelectorAll('[data-bulk-row]').forEach((row, index) => {
        row.querySelectorAll('input[name]').forEach((input) => {
          input.name = input.name.replace(/items\[\d+]/, 'items[' + index + ']');
        });
      });
    };

    const fillRows = (tbody, items) => {
      tbody.innerHTML = '';
      const list = items && items.length ? items : [{ title: '', price: 0, is_default: false }];
      list.forEach((item, index) => {
        const html = template.innerHTML.replaceAll('__INDEX__', String(index));
        tbody.insertAdjacentHTML('beforeend', html);
        const row = tbody.lastElementChild;
        const titleInput = row.querySelector('input[name*="[title]"]');
        const priceInput = row.querySelector('input[name*="[price]"]');
        const defaultInput = row.querySelector('input[type="checkbox"][name*="[is_default]"]');
        if (titleInput) titleInput.value = item.title || '';
        if (priceInput) priceInput.value = item.price ?? 0;
        if (defaultInput) defaultInput.checked = !!item.is_default;
      });
      reindexRows(tbody);
    };

    root.addEventListener('click', (event) => {
      const addBtn = event.target.closest('[data-bulk-add-row], [data-new-group-add-row]');
      if (addBtn) {
        const scope = addBtn.closest('[data-bulk-form], [data-new-group-form], [id^="property-group-"], [data-new-group-panel]');
        const tbody = scope?.querySelector('[data-bulk-rows], [data-new-group-rows]');
        if (!tbody) return;
        const html = template.innerHTML.replaceAll('__INDEX__', String(tbody.querySelectorAll('[data-bulk-row]').length));
        tbody.insertAdjacentHTML('beforeend', html);
        reindexRows(tbody);
        return;
      }

      const removeBtn = event.target.closest('[data-bulk-remove-row]');
      if (removeBtn) {
        const row = removeBtn.closest('[data-bulk-row]');
        const tbody = removeBtn.closest('[data-bulk-rows], [data-new-group-rows]');
        if (!row || !tbody) return;
        if (tbody.querySelectorAll('[data-bulk-row]').length <= 1) {
          row.querySelectorAll('input[type="text"], input[type="number"]').forEach((input) => {
            input.value = input.type === 'number' ? '0' : '';
          });
          row.querySelectorAll('input[type="checkbox"]').forEach((input) => { input.checked = false; });
          return;
        }
        row.remove();
        reindexRows(tbody);
      }
    });

    const templateSelect = root.querySelector('[data-template-select]');
    const newGroupForm = root.querySelector('[data-new-group-form]');
    if (templateSelect && newGroupForm) {
      templateSelect.addEventListener('change', () => {
        const id = parseInt(templateSelect.value || '0', 10);
        if (!id) return;
        const found = templates.find((row) => Number(row.id) === id);
        if (!found) return;

        const titleInput = newGroupForm.querySelector('[data-new-group-title]');
        const typeSelect = newGroupForm.querySelector('[data-new-group-type]');
        const requiredInput = newGroupForm.querySelector('[data-new-group-required]');
        const tbody = newGroupForm.querySelector('[data-new-group-rows]');
        const paste = newGroupForm.querySelector('[data-new-group-paste]');

        if (titleInput) titleInput.value = found.title || '';
        if (typeSelect) typeSelect.value = found.type || 'single';
        if (requiredInput) requiredInput.checked = !!found.is_required;
        if (paste) paste.value = '';
        if (tbody) fillRows(tbody, found.items || []);
      });
    }

    const hashTarget = document.getElementById((location.hash || '').replace(/^#/, ''));
    if (hashTarget && hashTarget.matches('details[id^="property-group-"]')) {
      hashTarget.open = true;
      hashTarget.scrollIntoView({ block: 'nearest' });
    }
  })();
</script>
