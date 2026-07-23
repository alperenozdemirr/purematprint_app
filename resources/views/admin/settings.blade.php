@extends('admin.layout')
@section('title', 'Sistem Ayarları')
@section('page_title', 'Sistem Ayarları')
@section('breadcrumb', 'Sistem / Ayarlar')

@section('content')
  <div class="mb-6">
    <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Sistem Ayarları</h2>
    <p class="font-body text-[13px] text-muted">Site durumu, kargo ve indirim kurallarını yönetin</p>
  </div>

  <form action="{{ route('admin.settingsUpdate') }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
    @csrf

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Site Durumu</h3>
      </div>
      <div class="grid gap-5 p-5">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="hidden" name="site_open" value="0">
          <input type="checkbox" name="site_open" value="1" class="h-4 w-4 accent-accent" @checked(old('site_open', $setting->site_open))>
          <span class="font-body text-[14px] text-ink">Site açık (kapalıysa ziyaretçiler bakım sayfasını görür)</span>
        </label>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">İndirim Ayarları</h3>
      </div>
      <div class="grid gap-5 p-5">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="hidden" name="discount_enabled" value="0">
          <input type="checkbox" id="discount_enabled" name="discount_enabled" value="1" class="h-4 w-4 accent-accent" @checked(old('discount_enabled', $setting->discount_enabled))>
          <span class="font-body text-[14px] text-ink">İndirim aktif</span>
        </label>

        <div id="discount-fields" class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="discount_type" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Türü</label>
            <select id="discount_type" name="discount_type" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
              <option value="">Seçin</option>
              @foreach ($discountTypes as $type)
                <option value="{{ $type->value }}" @selected(old('discount_type', $setting->discount_type?->value) === $type->value)>{{ $type->label() }}</option>
              @endforeach
            </select>
            @error('discount_type') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="discount_value" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Değeri</label>
            <input type="number" step="0.01" min="0" id="discount_value" name="discount_value" value="{{ old('discount_value', $setting->discount_value) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Örn. 10 veya 50">
            <p id="discount_value_hint" class="mt-1.5 font-body text-[12px] text-muted hidden">Yüzdelik indirim en fazla 100 olabilir.</p>
            @error('discount_value') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div class="md:col-span-2">
            <label for="discount_scope" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İndirim Kapsamı</label>
            <select id="discount_scope" name="discount_scope" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
              @foreach ($discountScopes as $scope)
                <option value="{{ $scope->value }}" @selected(old('discount_scope', $setting->discount_scope?->value) === $scope->value)>{{ $scope->label() }}</option>
              @endforeach
            </select>
            @error('discount_scope') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Kargo Ayarları</h3>
      </div>
      <div class="grid gap-5 p-5">
        <div>
          <label for="shipping_mode" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Modu</label>
          <select id="shipping_mode" name="shipping_mode" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            @foreach ($shippingModes as $mode)
              <option value="{{ $mode->value }}" @selected(old('shipping_mode', $setting->shipping_mode?->value) === $mode->value)>{{ $mode->label() }}</option>
            @endforeach
          </select>
          @error('shipping_mode') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div id="shipping-paid-fields" class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="shipping_fee" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Ücreti (₺)</label>
            <input type="number" step="0.01" min="0" id="shipping_fee" name="shipping_fee" value="{{ old('shipping_fee', $setting->shipping_fee) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            @error('shipping_fee') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>

          <div class="md:col-span-2">
            <label class="flex items-center gap-3 cursor-pointer mb-3">
              <input type="hidden" name="shipping_free_limit_enabled" value="0">
              <input type="checkbox" id="shipping_free_limit_enabled" name="shipping_free_limit_enabled" value="1" class="h-4 w-4 accent-accent" @checked(old('shipping_free_limit_enabled', $setting->shipping_free_limit_enabled))>
              <span class="font-body text-[14px] text-ink">Belirli tutar üzeri ücretsiz kargo uygula</span>
            </label>
            <input type="number" step="0.01" min="0" id="shipping_free_limit" name="shipping_free_limit" value="{{ old('shipping_free_limit', $setting->shipping_free_limit) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                   placeholder="Örn. 1000">
            @error('shipping_free_limit') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Logo</h3>
      </div>
      <div class="grid gap-5 p-5">
        @if ($setting->hasCustomLogo())
          <div class="flex items-center gap-4">
            <img src="{{ $setting->logoUrl() }}" alt="Site logosu" class="h-12 w-auto max-w-[200px] object-contain border border-ink/10 rounded-lg bg-cream p-2">
            <p class="font-body text-[13px] text-muted">Mevcut logo yüklü. Yeni dosya seçerseniz eskisi silinir.</p>
          </div>
        @else
          <p class="font-body text-[13px] text-muted">Logo yüklenmemiş — sitede varsayılan logo gösterilir.</p>
        @endif
        <div>
          <label for="logo" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Logo Yükle</label>
          <input type="file" id="logo" name="logo" accept="image/*"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('logo') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">İletişim Bilgileri</h3>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div>
          <label for="email" class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
          <input type="email" id="email" name="email" value="{{ old('email', $setting->email) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('email') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="mobile_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Cep Telefonu</label>
          <input type="text" id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone', $setting->mobile_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="0532 123 45 67">
          @error('mobile_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="business_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İş Telefonu</label>
          <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $setting->business_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('business_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="whatsapp_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">WhatsApp Numarası</label>
          <input type="text" id="whatsapp_phone" name="whatsapp_phone" value="{{ old('whatsapp_phone', $setting->whatsapp_phone) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="905321234567">
          @error('whatsapp_phone') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="address" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Adres</label>
          <textarea id="address" name="address" rows="3"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">{{ old('address', $setting->address) }}</textarea>
          @error('address') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
          <label for="short_info" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kısa Bilgi (Footer)</label>
          <textarea id="short_info" name="short_info" rows="2"
                    class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                    placeholder="Footer altında gösterilecek kısa açıklama">{{ old('short_info', $setting->short_info) }}</textarea>
          @error('short_info') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Sosyal Medya</h3>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div>
          <label for="instagram_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Instagram</label>
          <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15"
                 placeholder="https://instagram.com/...">
          @error('instagram_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="twitter_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Twitter / X</label>
          <input type="url" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('twitter_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="facebook_url" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Facebook</label>
          <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          @error('facebook_url') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <button type="submit" class="inline-flex w-full max-w-xs items-center justify-center rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      Ayarları Kaydet
    </button>
  </form>
@endsection

@section('scripts')
<script>
  (function () {
    const discountEnabled = document.getElementById('discount_enabled');
    const discountFields = document.getElementById('discount-fields');
    const discountType = document.getElementById('discount_type');
    const discountValue = document.getElementById('discount_value');
    const discountValueHint = document.getElementById('discount_value_hint');
    const shippingMode = document.getElementById('shipping_mode');
    const shippingPaidFields = document.getElementById('shipping-paid-fields');
    const freeLimitEnabled = document.getElementById('shipping_free_limit_enabled');
    const freeLimitInput = document.getElementById('shipping_free_limit');

    function toggleDiscountFields() {
      if (!discountEnabled || !discountFields) return;
      discountFields.style.display = discountEnabled.checked ? 'grid' : 'none';
    }

    function toggleDiscountValueLimit() {
      if (!discountType || !discountValue) return;

      const isPercent = discountType.value === 'percent';

      if (isPercent) {
        discountValue.max = '100';
        discountValueHint?.classList.remove('hidden');
      } else {
        discountValue.removeAttribute('max');
        discountValueHint?.classList.add('hidden');
      }

      if (isPercent && discountValue.value !== '' && Number(discountValue.value) > 100) {
        discountValue.value = '100';
      }
    }

    function toggleShippingFields() {
      if (!shippingMode || !shippingPaidFields) return;
      shippingPaidFields.style.display = shippingMode.value === 'paid' ? 'grid' : 'none';
    }

    function toggleFreeLimitInput() {
      if (!freeLimitEnabled || !freeLimitInput) return;
      freeLimitInput.disabled = !freeLimitEnabled.checked;
      freeLimitInput.closest('div')?.classList.toggle('opacity-50', !freeLimitEnabled.checked);
    }

    discountEnabled?.addEventListener('change', toggleDiscountFields);
    discountType?.addEventListener('change', toggleDiscountValueLimit);
    discountValue?.addEventListener('input', toggleDiscountValueLimit);
    shippingMode?.addEventListener('change', toggleShippingFields);
    freeLimitEnabled?.addEventListener('change', toggleFreeLimitInput);

    toggleDiscountFields();
    toggleDiscountValueLimit();
    toggleShippingFields();
    toggleFreeLimitInput();
  })();
</script>
@endsection
