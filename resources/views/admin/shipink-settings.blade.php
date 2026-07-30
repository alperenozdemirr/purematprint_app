@extends('admin.layout')
@section('title', 'Shipink Ayarları')
@section('page_title', 'Shipink Ayarları')
@section('breadcrumb', 'Satış / Shipink')

@section('content')
  <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Shipink Kargo Ayarları</h2>
      <p class="font-body text-[13px] text-muted">Depo, kargo hesabı ve ödeme kartını panelden seçin — UUID kopyalamaya gerek yok</p>
    </div>
    @if ($hasCredentials)
      <form action="{{ route('admin.shipinkSettingsRefresh') }}" method="POST">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg border-2 border-ink bg-surface px-4 py-2.5 font-body text-[13px] font-bold text-ink transition-colors hover:bg-cream">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-2.64-6.36M21 3v6h-6"/></svg>
          Shipink'ten Yeniden Sorgula
        </button>
      </form>
    @endif
  </div>

  <section class="mb-6 overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="border-b border-ink/10 px-5 py-4">
      <h3 class="font-heading text-[16px] font-bold text-ink">Bağlantı Durumu</h3>
    </div>
    <div class="grid gap-3 p-5 font-body text-[14px]">
      <div class="flex flex-wrap items-center gap-2">
        <span class="font-bold text-ink">API:</span>
        <span class="text-muted">{{ $connection['base_url'] }}</span>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <span class="font-bold text-ink">Kimlik bilgileri:</span>
        @if ($hasCredentials)
          <span class="rounded-full bg-success/10 px-2.5 py-1 text-[12px] font-semibold text-success">.env üzerinden tanımlı</span>
        @else
          <span class="rounded-full bg-danger/10 px-2.5 py-1 text-[12px] font-semibold text-danger">SHIPINK_USERNAME / SHIPINK_PASSWORD eksik</span>
        @endif
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <span class="font-bold text-ink">Seçimler:</span>
        @if ($configured)
          <span class="rounded-full bg-success/10 px-2.5 py-1 text-[12px] font-semibold text-success">Kargo oluşturmaya hazır</span>
        @else
          <span class="rounded-full bg-warning/10 px-2.5 py-1 text-[12px] font-semibold text-warning">Depo ve kargo hesabı seçilmeli</span>
        @endif
      </div>
      @if ($connection['error'])
        <div class="rounded-lg border border-danger/20 bg-danger/10 px-4 py-3 text-[13px] font-medium text-danger">
          {{ $connection['error'] }}
        </div>
      @elseif ($connection['ok'])
        <p class="text-[13px] text-muted">
          {{ count($connection['warehouses']) }} depo,
          {{ count($connection['carrier_accounts']) }} kargo hesabı,
          {{ count($connection['cards']) }} ödeme kartı bulundu.
        </p>
        @php
          $selectedAccount = collect($connection['carrier_accounts'])->first(
            fn ($account) => ($account['id'] ?? '') === old('shipink_carrier_account_id', $setting->shipink_carrier_account_id)
          );
        @endphp
        @if (($selectedAccount['carrier_id'] ?? '') === 'aras' && ($selectedAccount['provider'] ?? '') === 'shipink')
          <div class="rounded-lg border border-warning/30 bg-warning/10 px-4 py-3 font-body text-[13px] text-ink">
            <p class="font-bold">Aras (Shipink anlaşması) seçili</p>
            <p class="mt-1 text-[12px]">Bu hesapla kargo için adresinizin Aras'ta <strong>sevk adresi</strong> olarak kayıtlı olması gerekir. Değilse Shipink destek veya kendi Aras sözleşmenizi panelden ekleyin. Geçici çözüm: PTT hesabını seçin.</p>
          </div>
        @endif
      @endif
      <p class="text-[12px] text-muted">API kullanıcı adı ve şifresi güvenlik için yalnızca sunucu `.env` dosyasında tutulur.</p>
    </div>
  </section>

  <form action="{{ route('admin.shipinkSettingsUpdate') }}" method="POST" class="grid gap-6">
    @csrf

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Depo ve Kargo Hesabı</h3>
      </div>
      <div class="grid gap-5 p-5 md:grid-cols-2">
        <div>
          <label for="shipink_warehouse_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Gönderim Deposu</label>
          <select id="shipink_warehouse_id" name="shipink_warehouse_id"
                  class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            <option value="">Depo seçin</option>
            @foreach ($connection['warehouses'] as $warehouse)
              <option value="{{ $warehouse['id'] ?? '' }}"
                      data-label="{{ $warehouse['name'] ?? 'Depo' }}"
                      @selected(old('shipink_warehouse_id', $setting->shipink_warehouse_id ?: config('shipink.warehouse_id')) === ($warehouse['id'] ?? ''))>
                {{ $warehouse['name'] ?? 'Depo' }}
              </option>
            @endforeach
          </select>
          <input type="hidden" id="shipink_warehouse_name" name="shipink_warehouse_name" value="{{ old('shipink_warehouse_name', $setting->shipink_warehouse_name) }}">
          @if ($setting->shipink_warehouse_id && ! $connection['ok'])
            <p class="mt-1.5 font-body text-[12px] text-muted">Kayıtlı: {{ $setting->shipink_warehouse_name ?: $setting->shipink_warehouse_id }}</p>
          @endif
          @error('shipink_warehouse_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="shipink_carrier_account_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Firması / Hesap</label>
          <select id="shipink_carrier_account_id" name="shipink_carrier_account_id"
                  class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            <option value="">Kargo hesabı seçin</option>
            @foreach ($connection['carrier_accounts'] as $account)
              @php
                $carrier = strtoupper((string) ($account['carrier_id'] ?? 'unknown'));
                $provider = (string) ($account['provider'] ?? '');
                $providerLabel = $provider === 'shipink' ? 'Shipink anlaşması' : 'Kendi sözleşmem';
                $optionLabel = "{$carrier} ({$providerLabel})";
              @endphp
              <option value="{{ $account['id'] ?? '' }}"
                      data-label="{{ $optionLabel }}"
                      data-provider="{{ $provider }}"
                      data-services='@json($account['carrier_services'] ?? [])'
                      @selected(old('shipink_carrier_account_id', $setting->shipink_carrier_account_id ?: config('shipink.carrier_account_id')) === ($account['id'] ?? ''))>
                {{ $optionLabel }}
              </option>
            @endforeach
          </select>
          <input type="hidden" id="shipink_carrier_account_label" name="shipink_carrier_account_label" value="{{ old('shipink_carrier_account_label', $setting->shipink_carrier_account_label) }}">
          @if ($setting->shipink_carrier_account_id && ! $connection['ok'])
            <p class="mt-1.5 font-body text-[12px] text-muted">Kayıtlı: {{ $setting->shipink_carrier_account_label ?: $setting->shipink_carrier_account_id }}</p>
          @endif
          @error('shipink_carrier_account_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="shipink_carrier_service_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Kargo Servisi</label>
          <select id="shipink_carrier_service_id" name="shipink_carrier_service_id"
                  class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            <option value="">Önce kargo hesabı seçin</option>
          </select>
          @error('shipink_carrier_service_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="shipink_card_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ödeme Kartı</label>
          <select id="shipink_card_id" name="shipink_card_id"
                  class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            <option value="">Kart seçin (gerekirse)</option>
            @foreach ($connection['cards'] as $card)
              @php
                $cardLabel = trim(($card['name'] ?? 'Kart').' '.($card['card_number'] ?? ''));
              @endphp
              <option value="{{ $card['id'] ?? '' }}"
                      data-label="{{ $cardLabel }}"
                      @selected(old('shipink_card_id', $setting->shipink_card_id ?: config('shipink.card_id')) === ($card['id'] ?? ''))>
                {{ $cardLabel }}@if ($card['default'] ?? false) (varsayılan)@endif
              </option>
            @endforeach
          </select>
          <input type="hidden" id="shipink_card_label" name="shipink_card_label" value="{{ old('shipink_card_label', $setting->shipink_card_label) }}">
          <p id="shipink-card-hint" class="mt-1.5 hidden font-body text-[12px] text-warning"></p>
          @if ($setting->shipink_card_id && ! $connection['ok'])
            <p class="mt-1.5 font-body text-[12px] text-muted">Kayıtlı: {{ $setting->shipink_card_label ?: $setting->shipink_card_id }}</p>
          @endif
          @error('shipink_card_id') <p class="mt-1.5 font-body text-[12px] font-medium text-danger">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    @php
      $wh = is_array($selectedWarehouse ?? null) ? $selectedWarehouse : [];
      $whCompany = is_array($wh['company'] ?? null) ? $wh['company'] : [];
      $whAddress = is_array($wh['address'] ?? null) ? $wh['address'] : [];
      $whPhone = is_array($wh['phone'] ?? null) ? $wh['phone'] : [];
      $whEmail = is_array($wh['email'] ?? null) ? ($wh['email']['main'] ?? '') : ($wh['email'] ?? '');
      $displayPhone = old('shipink_sender_phone', $whPhone['main'] ?? '');
    @endphp

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Gönderici Adresi (Depo)</h3>
        <p class="mt-1 font-body text-[12px] text-muted">Aras Kargo gönderici bilgilerini buradan düzenleyin. Kaydettiğinizde Shipink deposu güncellenir.</p>
      </div>
      <div class="grid gap-5 p-5">
        @if ($warehouseIssues !== [])
          <div class="rounded-lg border border-warning/30 bg-warning/10 px-4 py-3 font-body text-[13px] text-ink">
            <p class="font-bold">Eksik veya hatalı alanlar:</p>
            <ul class="mt-2 list-inside list-disc text-[12px]">
              @foreach ($warehouseIssues as $issue)
                <li>{{ $issue }}</li>
              @endforeach
            </ul>
          </div>
        @elseif ($selectedWarehouse)
          <div class="rounded-lg border border-success/20 bg-success/10 px-4 py-3 font-body text-[12px] font-semibold text-success">
            Gönderici adresi Shipink formatına uygun görünüyor.
          </div>
        @endif

        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="shipink_sender_name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Depo Adı</label>
            <input type="text" id="shipink_sender_name" name="shipink_sender_name"
                   value="{{ old('shipink_sender_name', $wh['name'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_person_name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yetkili Adı</label>
            <input type="text" id="shipink_sender_person_name" name="shipink_sender_person_name"
                   value="{{ old('shipink_sender_person_name', $wh['person_name'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_company_name" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Firma Adı</label>
            <input type="text" id="shipink_sender_company_name" name="shipink_sender_company_name"
                   value="{{ old('shipink_sender_company_name', $whCompany['name'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_tax_id" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Vergi No</label>
            <input type="text" id="shipink_sender_tax_id" name="shipink_sender_tax_id"
                   value="{{ old('shipink_sender_tax_id', $whCompany['tax_id'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_tax_office" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Vergi Dairesi</label>
            <input type="text" id="shipink_sender_tax_office" name="shipink_sender_tax_office"
                   value="{{ old('shipink_sender_tax_office', $whCompany['tax_office'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_phone" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Telefon</label>
            <input type="text" id="shipink_sender_phone" name="shipink_sender_phone"
                   value="{{ $displayPhone }}"
                   placeholder="905XXXXXXXXX"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
            <p class="mt-1.5 font-body text-[12px] text-muted">Shipink formatı: 90 ile başlayan 12 haneli numara (ör. 905458145563)</p>
          </div>
          <div class="md:col-span-2">
            <label for="shipink_sender_email" class="mb-1.5 block font-body text-[13px] font-bold text-ink">E-posta</label>
            <input type="email" id="shipink_sender_email" name="shipink_sender_email"
                   value="{{ old('shipink_sender_email', $whEmail) }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div class="md:col-span-2">
            <label for="shipink_sender_street" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Adres</label>
            <input type="text" id="shipink_sender_street" name="shipink_sender_street"
                   value="{{ old('shipink_sender_street', $whAddress['street'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_city" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İlçe</label>
            <input type="text" id="shipink_sender_city" name="shipink_sender_city"
                   value="{{ old('shipink_sender_city', $whAddress['city'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_state" class="mb-1.5 block font-body text-[13px] font-bold text-ink">İl</label>
            <input type="text" id="shipink_sender_state" name="shipink_sender_state"
                   value="{{ old('shipink_sender_state', $whAddress['state'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
          <div>
            <label for="shipink_sender_zip" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Posta Kodu</label>
            <input type="text" id="shipink_sender_zip" name="shipink_sender_zip"
                   value="{{ old('shipink_sender_zip', $whAddress['zip'] ?? '') }}"
                   class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
          </div>
        </div>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Varsayılan Paket Ölçüleri</h3>
      </div>
      <div class="grid gap-5 p-5 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <label for="shipink_default_weight" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Ağırlık (kg)</label>
          <input type="number" min="1" max="100" id="shipink_default_weight" name="shipink_default_weight"
                 value="{{ old('shipink_default_weight', $setting->shipink_default_weight ?: 1) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
        </div>
        <div>
          <label for="shipink_default_length" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Uzunluk (cm)</label>
          <input type="number" min="1" max="300" id="shipink_default_length" name="shipink_default_length"
                 value="{{ old('shipink_default_length', $setting->shipink_default_length ?: 20) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
        </div>
        <div>
          <label for="shipink_default_width" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Genişlik (cm)</label>
          <input type="number" min="1" max="300" id="shipink_default_width" name="shipink_default_width"
                 value="{{ old('shipink_default_width', $setting->shipink_default_width ?: 15) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
        </div>
        <div>
          <label for="shipink_default_height" class="mb-1.5 block font-body text-[13px] font-bold text-ink">Yükseklik (cm)</label>
          <input type="number" min="1" max="300" id="shipink_default_height" name="shipink_default_height"
                 value="{{ old('shipink_default_height', $setting->shipink_default_height ?: 10) }}"
                 class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent focus:ring-2 focus:ring-accent/15">
        </div>
      </div>
    </section>

    <div class="flex justify-end">
      <button type="submit"
              class="rounded-lg border-2 border-ink bg-accent px-6 py-3 font-body text-[14px] font-bold text-on-dark shadow-[3px_3px_0_rgba(0,0,0,0.35)] transition-transform hover:-translate-y-0.5">
        Ayarları Kaydet
      </button>
    </div>
  </form>
@endsection

@section('scripts')
<script>
(() => {
  const warehouseSelect = document.getElementById('shipink_warehouse_id');
  const warehouseNameInput = document.getElementById('shipink_warehouse_name');
  const accountSelect = document.getElementById('shipink_carrier_account_id');
  const accountLabelInput = document.getElementById('shipink_carrier_account_label');
  const serviceSelect = document.getElementById('shipink_carrier_service_id');
  const cardSelect = document.getElementById('shipink_card_id');
  const cardLabelInput = document.getElementById('shipink_card_label');
  const cardHint = document.getElementById('shipink-card-hint');
  const savedServiceId = @json(old('shipink_carrier_service_id', $setting->shipink_carrier_service_id ?: config('shipink.carrier_service_id')));

  const syncHiddenLabel = (select, hiddenInput) => {
    const option = select.options[select.selectedIndex];
    hiddenInput.value = option?.dataset?.label || '';
  };

  const renderServices = () => {
    const option = accountSelect.options[accountSelect.selectedIndex];
    const services = option?.dataset?.services ? JSON.parse(option.dataset.services) : [];
    const provider = option?.dataset?.provider || '';

    serviceSelect.innerHTML = '';

    if (!option?.value) {
      serviceSelect.innerHTML = '<option value="">Önce kargo hesabı seçin</option>';
      cardHint.classList.add('hidden');
      return;
    }

    if (services.length === 0) {
      serviceSelect.innerHTML = '<option value="">Servis bulunamadı</option>';
    } else {
      services.forEach((service) => {
        const el = document.createElement('option');
        el.value = service.id || '';
        el.textContent = service.id || 'Servis';
        if (savedServiceId && savedServiceId === el.value) {
          el.selected = true;
        }
        serviceSelect.appendChild(el);
      });
    }

    if (provider === 'shipink') {
      cardHint.textContent = 'Bu hesap Shipink anlaşmasıdır. Kargo oluşturmak için ödeme kartı seçmeniz gerekir.';
      cardHint.classList.remove('hidden');
    } else {
      cardHint.classList.add('hidden');
    }
  };

  warehouseSelect?.addEventListener('change', () => syncHiddenLabel(warehouseSelect, warehouseNameInput));
  accountSelect?.addEventListener('change', () => {
    syncHiddenLabel(accountSelect, accountLabelInput);
    renderServices();
  });
  cardSelect?.addEventListener('change', () => syncHiddenLabel(cardSelect, cardLabelInput));

  syncHiddenLabel(warehouseSelect, warehouseNameInput);
  syncHiddenLabel(accountSelect, accountLabelInput);
  syncHiddenLabel(cardSelect, cardLabelInput);
  renderServices();
})();
</script>
@endsection
