@props([
    'name' => 'rating',
    'value' => null,
    'required' => true,
])

<div class="star-rating-input" data-star-rating>
  <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}" data-star-value @if($required) required @endif>
  <div class="inline-flex items-center gap-1" role="radiogroup" aria-label="Puan">
    @for ($i = 1; $i <= 5; $i++)
      <button type="button"
              data-star="{{ $i }}"
              aria-label="{{ $i }} yıldız"
              class="relative m-0 inline-flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center overflow-hidden border-0 bg-transparent p-0">
        <span class="pointer-events-none flex h-8 w-8 items-center justify-center text-[1.75rem] leading-none text-ink/20" aria-hidden="true">★</span>
        <span class="pointer-events-none absolute left-0 top-0 h-8 overflow-hidden" data-star-fill style="width: 0" aria-hidden="true">
          <span class="flex h-8 w-8 items-center justify-center text-[1.75rem] leading-none text-[#f59e0b]">★</span>
        </span>
      </button>
    @endfor
  </div>
  <p class="mt-1.5 text-[12px] text-muted" data-star-label>Puan seçin · yarım yıldız için sol/sağ tıklayın</p>
</div>

@once
  @push('scripts')
  <script>
    document.querySelectorAll('[data-star-rating]').forEach((root) => {
      const input = root.querySelector('[data-star-value]');
      const buttons = Array.from(root.querySelectorAll('[data-star]'));
      const label = root.querySelector('[data-star-label]');

      const formatRating = (value) => {
        const rating = Number(value) || 0;
        if (rating <= 0) return '';
        return Number.isInteger(rating) ? String(rating) : rating.toFixed(1);
      };

      const paint = (value) => {
        const rating = Number(value) || 0;
        buttons.forEach((btn) => {
          const star = Number(btn.dataset.star);
          const fillEl = btn.querySelector('[data-star-fill]');
          let width = 0;
          if (rating >= star) {
            width = 100;
          } else if (rating >= star - 0.5) {
            width = 50;
          }
          fillEl.style.width = `${width}%`;
        });

        const formatted = formatRating(rating);
        label.textContent = formatted ? `${formatted} / 5 yıldız` : 'Puan seçin · yarım yıldız için sol/sağ tıklayın';
      };

      const pickRating = (btn, clientX) => {
        const rect = btn.getBoundingClientRect();
        const isLeftHalf = clientX - rect.left < rect.width / 2;
        const star = Number(btn.dataset.star);
        return isLeftHalf ? star - 0.5 : star;
      };

      paint(input.value);

      buttons.forEach((btn) => {
        btn.addEventListener('click', (event) => {
          input.value = pickRating(btn, event.clientX);
          paint(input.value);
        });

        btn.addEventListener('mousemove', (event) => {
          paint(pickRating(btn, event.clientX));
        });
      });

      root.addEventListener('mouseleave', () => paint(input.value));
    });
  </script>
  @endpush
@endonce
