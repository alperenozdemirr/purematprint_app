@extends('admin.layout')
@section('title', 'Yeni Ürün')
@section('page_title', 'Yeni Ürün')
@section('breadcrumb', 'Katalog / Ürünler / Yeni Ürün')

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.productList') }}" aria-label="Geri"
       class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-hover">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Yeni Ürün Ekle</h2>
      <p class="font-body text-[13px] text-muted">Ürün bilgilerini doldurup kaydedin</p>
    </div>
  </div>

  <form action="{{ route('admin.productStore') }}" method="POST" enctype="multipart/form-data" data-product-form>
    @csrf

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Genel Bilgiler</h3>
          </div>
          <div class="grid grid-cols-1 gap-5 p-5">
            <div>
              <label for="title" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Adı <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" value="{{ old('title') }}" required
                     class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                     placeholder="Örn. LED Lightbox Tabela">
              @error('title') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div>
                <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Kodu</label>
                <p class="rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted">Kayıt sırasında otomatik oluşturulur</p>
              </div>
              <div>
                <label class="mb-1.5 block font-body text-[13px] font-bold text-ink">Slug</label>
                <p class="rounded-lg border border-ink/10 bg-cream/60 px-3.5 py-2.5 font-body text-[14px] text-muted">Ürün adı ve kodundan otomatik oluşturulur</p>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div>
                <label for="price" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Fiyat (₺) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}" required
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                       placeholder="0.00">
                @error('price') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>
              <div>
                <label for="stock_count" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Stok Adedi</label>
                <input type="number" min="0" id="stock_count" name="stock_count" value="{{ old('stock_count', 0) }}"
                       class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                       placeholder="0">
                @error('stock_count') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
              </div>
            </div>

            <div>
              <p class="mb-3 font-body text-[13px] font-bold text-ink">Kargo Ölçüleri <span class="font-medium text-muted">(opsiyonel)</span></p>
              <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div>
                  <label for="shipping_weight" class="mb-1.5 block font-body text-[12px] font-bold text-muted">Ağırlık (kg)</label>
                  <input type="number" step="0.001" min="0.001" id="shipping_weight" name="shipping_weight" value="{{ old('shipping_weight') }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent"
                         placeholder="1.000">
                  @error('shipping_weight') <p class="mt-1 font-body text-[11px] text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="shipping_length" class="mb-1.5 block font-body text-[12px] font-bold text-muted">Boy (cm)</label>
                  <input type="number" min="1" id="shipping_length" name="shipping_length" value="{{ old('shipping_length') }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent"
                         placeholder="20">
                  @error('shipping_length') <p class="mt-1 font-body text-[11px] text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="shipping_width" class="mb-1.5 block font-body text-[12px] font-bold text-muted">En (cm)</label>
                  <input type="number" min="1" id="shipping_width" name="shipping_width" value="{{ old('shipping_width') }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent"
                         placeholder="15">
                  @error('shipping_width') <p class="mt-1 font-body text-[11px] text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label for="shipping_height" class="mb-1.5 block font-body text-[12px] font-bold text-muted">Yükseklik (cm)</label>
                  <input type="number" min="1" id="shipping_height" name="shipping_height" value="{{ old('shipping_height') }}"
                         class="w-full rounded-lg border border-ink/10 bg-cream px-3 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent"
                         placeholder="10">
                  @error('shipping_height') <p class="mt-1 font-body text-[11px] text-danger">{{ $message }}</p> @enderror
                </div>
              </div>
              <p class="mt-2 font-body text-[12px] text-muted">Kargo oluştururken sipariş ürünlerinden otomatik paket ölçüsü hesaplanır.</p>
            </div>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Açıklama</h3>
          </div>
          <div class="p-5">
            <div>
              <label for="description" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ürün Açıklaması</label>
              <textarea id="description" name="description" rows="8"
                        class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] leading-relaxed text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15"
                        placeholder="Ürünün detaylı açıklaması...">{{ old('description') }}</textarea>
              @error('description') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Ürün Görselleri</h3>
            <p class="mt-1 font-body text-[12px] text-muted">Sürükleyerek sıralayın. İlk görsel kapak olur.</p>
          </div>
          <div class="p-5">
            <label for="images"
                   class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-ink/15 bg-cream px-4 py-10 text-center transition-colors hover:border-accent/40 hover:bg-hover/50">
              <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-muted"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
              <span class="font-body text-[14px] font-bold text-ink">Görselleri sürükleyin veya seçin</span>
              <span class="font-body text-[12px] text-muted">{{ \App\Support\ImageUploadRules::humanList() }} — en fazla 40MB. Seçtikten sonra sıralayabilirsiniz.</span>
              <input type="file" id="images" name="images[]" accept="{{ \App\Support\ImageUploadRules::acceptAttribute() }}" multiple class="hidden" data-image-input>
            </label>
            @error('images') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            @error('images.*') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            <div class="mt-4 hidden grid-cols-3 gap-3 sm:grid-cols-4" data-image-preview></div>
          </div>
        </section>

        @include('admin.partials.product-properties-create')
      </div>

      <div class="flex flex-col gap-6">
        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Yayın</h3>
          </div>
          <div class="flex flex-col gap-5 p-5">
            <div>
              <label for="status" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Durum</label>
              <select id="status" name="status" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
                <option value="active" @selected(old('status') === 'active')>Aktif</option>
                <option value="passive" @selected(old('status') === 'passive')>Pasif</option>
              </select>
              @error('status') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
            </div>

            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg bg-cream px-3.5 py-3 transition-colors hover:bg-hover">
              <span>
                <span class="block font-body text-[13px] font-bold text-ink">Öne Çıkan Ürün</span>
                <span class="block font-body text-[12px] text-muted">Anasayfada vitrine çıkar</span>
              </span>
              <input type="checkbox" name="featured_status" value="1" @checked(old('featured_status')) class="h-5 w-5 shrink-0 accent-accent">
            </label>

            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg bg-cream px-3.5 py-3 transition-colors hover:bg-hover">
              <span>
                <span class="block font-body text-[13px] font-bold text-ink">Tanıtım Ürünü</span>
                <span class="block font-body text-[12px] text-muted">Koleksiyon tanıtımı için kullan</span>
              </span>
              <input type="checkbox" name="introduction_status" value="1" @checked(old('introduction_status')) class="h-5 w-5 shrink-0 accent-accent">
            </label>
          </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-surface shadow-card">
          <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-heading text-[16px] font-bold text-ink">Kategori</h3>
          </div>
          <div class="p-5">
            <label for="category_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kategori Seç <span class="text-danger">*</span></label>
            <select id="category_id" name="category_id" required class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] font-medium text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
              <option value="">Kategori seçin...</option>
              @foreach ($categoryOptions as $categoryOption)
                <option value="{{ $categoryOption['id'] }}" @selected(old('category_id') == $categoryOption['id'])>{{ $categoryOption['label'] }}</option>
              @endforeach
            </select>
            @error('category_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </section>

        <div class="flex flex-col gap-3 rounded-xl bg-surface p-5 shadow-card">
          <button type="submit"
                  class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2zM17 21v-8H7v8M7 3v5h8"/></svg>
            Ürünü Kaydet
          </button>
          <a href="{{ route('admin.productList') }}"
             class="inline-flex items-center justify-center rounded-lg bg-cream px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">
            İptal
          </a>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
  <script>
    (function () {
      const form = document.querySelector('[data-product-form]');
      if (!form) return;

      const imageInput = form.querySelector('[data-image-input]');
      const preview = form.querySelector('[data-image-preview]');
      let selectedFiles = [];
      let previewSortable = null;

      const syncInputFiles = () => {
        const dt = new DataTransfer();
        selectedFiles.forEach((file) => dt.items.add(file));
        imageInput.files = dt.files;
      };

      const renderPreview = () => {
        preview.innerHTML = '';
        if (!selectedFiles.length) {
          preview.classList.add('hidden');
          return;
        }
        preview.classList.remove('hidden');
        selectedFiles.forEach((file, i) => {
          const cell = document.createElement('div');
          cell.className = 'relative aspect-square cursor-grab overflow-hidden rounded-lg bg-cream active:cursor-grabbing';
          cell.innerHTML = '<img src="' + URL.createObjectURL(file) + '" class="pointer-events-none h-full w-full object-cover" alt="">' +
            (i === 0 ? '<span class="absolute left-1 top-1 rounded bg-accent px-1.5 py-0.5 font-body text-[10px] font-bold uppercase text-on-dark">Kapak</span>' : '');
          preview.appendChild(cell);
        });
      };

      const ensureSortable = () => {
        if (typeof Sortable === 'undefined' || previewSortable) return;
        previewSortable = Sortable.create(preview, {
          animation: 150,
          onEnd: (evt) => {
            if (evt.oldIndex === evt.newIndex) return;
            const moved = selectedFiles.splice(evt.oldIndex, 1)[0];
            selectedFiles.splice(evt.newIndex, 0, moved);
            syncInputFiles();
            renderPreview();
          }
        });
      };

      imageInput.addEventListener('change', () => {
        selectedFiles = Array.from(imageInput.files);
        renderPreview();
        ensureSortable();
      });

      form.addEventListener('submit', () => syncInputFiles());
    })();

    (function () {
      const root = document.querySelector('[data-create-property-manager]');
      if (!root) return;

      const groupsWrap = root.querySelector('[data-create-groups]');
      const groupTemplate = document.getElementById('create-property-group-template');
      const itemTemplate = document.getElementById('create-property-item-row-template');
      if (!groupsWrap || !groupTemplate || !itemTemplate) return;

      let templates = [];
      try {
        templates = JSON.parse(document.getElementById('create-property-templates-json')?.textContent || '[]');
      } catch (e) {
        templates = [];
      }

      const reindex = () => {
        groupsWrap.querySelectorAll('[data-create-group]').forEach((groupEl, gIndex) => {
          groupEl.querySelectorAll('[name]').forEach((input) => {
            input.name = input.name
              .replace(/property_groups\[(?:\d+|__G__)\]/, 'property_groups[' + gIndex + ']');
          });

          const tbody = groupEl.querySelector('[data-create-item-rows]');
          tbody?.querySelectorAll('[data-create-item-row]').forEach((row, iIndex) => {
            row.querySelectorAll('[name]').forEach((input) => {
              input.name = input.name.replace(/\[items\]\[(?:\d+|__I__)\]/, '[items][' + iIndex + ']');
            });
          });

          const titleInput = groupEl.querySelector('[data-create-group-title]');
          const summary = groupEl.querySelector('[data-create-group-summary-title]');
          if (titleInput && summary) {
            const syncTitle = () => {
              summary.textContent = titleInput.value.trim() || 'Yeni özellik grubu';
            };
            titleInput.removeEventListener('input', titleInput._syncTitle || (() => {}));
            titleInput._syncTitle = syncTitle;
            titleInput.addEventListener('input', syncTitle);
            syncTitle();
          }
        });
      };

      const addGroup = (preset = null) => {
        const html = groupTemplate.innerHTML.replaceAll('__G__', String(groupsWrap.querySelectorAll('[data-create-group]').length));
        groupsWrap.insertAdjacentHTML('beforeend', html);
        const groupEl = groupsWrap.lastElementChild;
        if (preset) {
          const title = groupEl.querySelector('[data-create-group-title]');
          const type = groupEl.querySelector('select[name*="[type]"]');
          const required = groupEl.querySelector('input[type="checkbox"][name*="[is_required]"]');
          const tbody = groupEl.querySelector('[data-create-item-rows]');
          if (title) title.value = preset.title || '';
          if (type) type.value = preset.type || 'single';
          if (required) required.checked = !!preset.is_required;
          if (tbody) {
            tbody.innerHTML = '';
            const items = preset.items && preset.items.length ? preset.items : [{ title: '', price: 0, is_default: false }];
            items.forEach((item, index) => {
              const rowHtml = itemTemplate.innerHTML
                .replaceAll('__G__', '0')
                .replaceAll('__I__', String(index));
              tbody.insertAdjacentHTML('beforeend', rowHtml);
              const row = tbody.lastElementChild;
              const titleInput = row.querySelector('input[name*="[title]"]');
              const priceInput = row.querySelector('input[name*="[price]"]');
              const defaultInput = row.querySelector('input[type="checkbox"][name*="[is_default]"]');
              if (titleInput) titleInput.value = item.title || '';
              if (priceInput) priceInput.value = item.price ?? 0;
              if (defaultInput) defaultInput.checked = !!item.is_default;
            });
          }
        }
        reindex();
      };

      root.querySelector('[data-create-add-group]')?.addEventListener('click', () => addGroup());

      root.addEventListener('click', (event) => {
        if (event.target.closest('[data-create-remove-group]')) {
          const groupEl = event.target.closest('[data-create-group]');
          if (!groupEl) return;
          if (groupsWrap.querySelectorAll('[data-create-group]').length <= 1) {
            groupEl.querySelectorAll('input[type="text"], input[type="number"], textarea').forEach((input) => {
              input.value = input.type === 'number' ? '0' : '';
            });
            groupEl.querySelectorAll('input[type="checkbox"]').forEach((input) => { input.checked = false; });
            reindex();
            return;
          }
          groupEl.remove();
          reindex();
          return;
        }

        if (event.target.closest('[data-create-add-item]')) {
          const groupEl = event.target.closest('[data-create-group]');
          const tbody = groupEl?.querySelector('[data-create-item-rows]');
          if (!tbody) return;
          const html = itemTemplate.innerHTML
            .replaceAll('__G__', '0')
            .replaceAll('__I__', String(tbody.querySelectorAll('[data-create-item-row]').length));
          tbody.insertAdjacentHTML('beforeend', html);
          reindex();
          return;
        }

        if (event.target.closest('[data-create-remove-item]')) {
          const row = event.target.closest('[data-create-item-row]');
          const tbody = event.target.closest('[data-create-item-rows]');
          if (!row || !tbody) return;
          if (tbody.querySelectorAll('[data-create-item-row]').length <= 1) {
            row.querySelectorAll('input[type="text"], input[type="number"]').forEach((input) => {
              input.value = input.type === 'number' ? '0' : '';
            });
            row.querySelectorAll('input[type="checkbox"]').forEach((input) => { input.checked = false; });
            return;
          }
          row.remove();
          reindex();
        }
      });

      const templateSelect = root.querySelector('[data-create-template-select]');
      templateSelect?.addEventListener('change', () => {
        const id = parseInt(templateSelect.value || '0', 10);
        if (!id) return;
        const found = templates.find((row) => Number(row.id) === id);
        if (!found) return;
        addGroup(found);
        templateSelect.value = '';
      });

      reindex();
    })();
  </script>
@endsection

@include('admin.partials.ckeditor', ['ckeditorElementId' => 'description', 'ckeditorHeight' => 280])
