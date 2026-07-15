@props([
    'products',
    'selectedProductIds' => [],
])

@php
  $selectedSet = collect($selectedProductIds)->map(fn ($id) => (int) $id)->all();
@endphp

<section class="overflow-hidden rounded-xl bg-surface shadow-card" data-collection-products>
  <div class="border-b border-ink/10 px-5 py-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <h3 class="font-heading text-[16px] font-bold text-ink">Koleksiyon Ürünleri</h3>
        <p class="mt-0.5 font-body text-[12px] text-muted">Bu koleksiyonda gösterilecek ürünleri seçin</p>
      </div>
      <span class="rounded-full bg-cream px-3 py-1 font-body text-[12px] font-bold text-ink" data-selected-count>
        Seçili: {{ count($selectedSet) }}
      </span>
    </div>
  </div>

  <div class="border-b border-ink/10 p-5">
    <input type="search"
           placeholder="Ürün adı veya kodu ile ara..."
           data-product-search
           class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none transition-colors focus:border-accent focus:ring-2 focus:ring-accent/15">
    <div class="mt-3 flex flex-wrap gap-2">
      <button type="button" data-select-all
              class="rounded-lg border border-ink/15 bg-cream px-3 py-1.5 font-body text-[12px] font-bold text-ink transition-colors hover:bg-hover">
        Görünenleri Seç
      </button>
      <button type="button" data-clear-all
              class="rounded-lg border border-ink/15 bg-cream px-3 py-1.5 font-body text-[12px] font-bold text-ink transition-colors hover:bg-hover">
        Tüm Seçimi Kaldır
      </button>
    </div>
  </div>

  <div class="max-h-[420px] overflow-y-auto p-3">
    <div class="grid gap-2" data-product-list>
      @forelse ($products as $product)
        @php
          $isSelected = in_array($product->id, $selectedSet, true);
          $thumb = $product->images->first()?->url;
          $searchText = Str::lower($product->title.' '.$product->code);
        @endphp
        <label data-product-item data-search="{{ $searchText }}"
               class="flex cursor-pointer items-center gap-3 rounded-lg border px-3 py-2.5 transition-colors {{ $isSelected ? 'border-accent bg-accent/5' : 'border-ink/10 hover:bg-hover/60' }}">
          <input type="checkbox"
                 name="product_ids[]"
                 value="{{ $product->id }}"
                 @checked($isSelected)
                 data-product-checkbox
                 class="h-4 w-4 shrink-0 rounded border-ink/20 text-accent focus:ring-accent/30">
          <div class="h-12 w-12 shrink-0 overflow-hidden rounded-md bg-cream">
            @if ($thumb)
              <img src="{{ $thumb }}" alt="{{ $product->title }}" class="h-full w-full object-cover">
            @else
              <div class="flex h-full w-full items-center justify-center text-muted">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
              </div>
            @endif
          </div>
          <div class="min-w-0 flex-1">
            <p class="truncate font-body text-[14px] font-bold text-ink">{{ $product->title }}</p>
            <p class="font-body text-[12px] text-muted">{{ $product->code }} · {{ number_format((float) $product->price, 0, ',', '.') }} ₺</p>
          </div>
          <span class="shrink-0 rounded-md px-2 py-0.5 font-body text-[11px] font-bold uppercase tracking-[0.04em] {{ $product->status === \App\Enums\Status::ACTIVE ? 'bg-[rgba(21,128,61,0.1)] text-[#15803d]' : 'bg-cream text-muted' }}">
            {{ $product->status->label() }}
          </span>
        </label>
      @empty
        <p class="px-3 py-6 text-center font-body text-[14px] text-muted">Henüz ürün bulunmuyor.</p>
      @endforelse
    </div>
  </div>
</section>

@once
  @push('scripts')
  <script>
    document.querySelectorAll('[data-collection-products]').forEach((root) => {
      const searchInput = root.querySelector('[data-product-search]');
      const items = Array.from(root.querySelectorAll('[data-product-item]'));
      const checkboxes = () => Array.from(root.querySelectorAll('[data-product-checkbox]'));
      const countEl = root.querySelector('[data-selected-count]');

      const updateCount = () => {
        const selected = checkboxes().filter((cb) => cb.checked).length;
        countEl.textContent = `Seçili: ${selected}`;
      };

      const visibleItems = () => items.filter((item) => item.style.display !== 'none');

      searchInput?.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();
        items.forEach((item) => {
          const haystack = item.dataset.search || '';
          item.style.display = haystack.includes(query) ? '' : 'none';
        });
      });

      checkboxes().forEach((cb) => {
        cb.addEventListener('change', () => {
          const label = cb.closest('[data-product-item]');
          if (!label) return;
          label.classList.toggle('border-accent', cb.checked);
          label.classList.toggle('bg-accent/5', cb.checked);
          label.classList.toggle('border-ink/10', !cb.checked);
          updateCount();
        });
      });

      root.querySelector('[data-select-all]')?.addEventListener('click', () => {
        visibleItems().forEach((item) => {
          const cb = item.querySelector('[data-product-checkbox]');
          if (cb) cb.checked = true;
          item.classList.add('border-accent', 'bg-accent/5');
          item.classList.remove('border-ink/10');
        });
        updateCount();
      });

      root.querySelector('[data-clear-all]')?.addEventListener('click', () => {
        checkboxes().forEach((cb) => { cb.checked = false; });
        items.forEach((item) => {
          item.classList.remove('border-accent', 'bg-accent/5');
          item.classList.add('border-ink/10');
        });
        updateCount();
      });
    });
  </script>
  @endpush
@endonce
