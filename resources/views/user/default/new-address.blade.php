@extends('user.layout')
@section('title', $address ? 'Adres Düzenle' : 'Yeni Adres Ekle')
@section('content')
@php
  $isEdit = (bool) $address;
  $addressType = 'home';
  $customLabel = '';

  if ($isEdit) {
      $addressType = match ($address->title) {
          'Ev' => 'home',
          'İş' => 'work',
          default => 'other',
      };
      if ($addressType === 'other') {
          $customLabel = $address->title;
      }
  }
@endphp
<main class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="{{ route('addressList') }}">Adreslerim</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span id="address-page-crumb">{{ $isEdit ? 'Adres Düzenle' : 'Yeni Adres' }}</span>
      </nav>

      <div class="grid gap-6 items-start min-[960px]:grid-cols-[260px_1fr] min-[960px]:gap-8" data-i5="account-layout">
        @include('user.partials.account-nav', ['activeNav' => $activeNav ?? 'addresses'])

        <div>
          <a href="{{ route('addressList') }}" class="inline-flex items-center gap-1.5 mb-5 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted transition-colors hover:text-ink" data-i5="address-new-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Adreslerime Dön
          </a>

          <div class="mb-6 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="address-new-page__head">
            <h1 id="address-page-title">{{ $isEdit ? 'Adres Düzenle' : 'Yeni Adres' }}</h1>
            <p class="mt-2.5 text-sm text-muted font-semibold" data-i5="address-new-page__sub">Teslimat süreçlerinde kullanılacak adres bilgilerini girin</p>
          </div>

          @if (session('error'))
          <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
          @endif

          <section class="border-[3px] border-ink shadow-brutal bg-surface overflow-hidden" data-i5="address-new-panel">
            <div class="px-6 py-[18px] border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-xs [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em] [&_p]:mt-1.5 [&_p]:text-[13px] [&_p]:text-muted" data-i5="address-new-panel__head">
              <h2>Adres Bilgileri</h2>
              <p>* ile işaretli alanlar zorunludur</p>
            </div>
            <form class="p-6 px-5 grid gap-5" id="address-form" action="{{ $isEdit ? route('addressUpdate') : route('addressStore') }}" method="post" data-i5="address-form">
              @csrf
              @if ($isEdit)
              <input type="hidden" name="id" value="{{ $address->id }}">
              @endif

              <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="Adres tipi" data-i5="address-form__types">
                @foreach (['home' => 'Ev', 'work' => 'İş', 'other' => 'Diğer'] as $typeValue => $typeLabel)
                <label class="flex items-center gap-1.5 px-3.5 py-2.5 border-[3px] border-ink shadow-brutal-sm font-body text-[11px] font-bold uppercase tracking-[0.06em] cursor-pointer transition-colors has-[:checked]:bg-action has-[:checked]:text-on-dark has-[:checked]:border-ink [&_input]:absolute [&_input]:opacity-0 [&_input]:pointer-events-none" data-i5="address-form__type">
                  <input type="radio" name="type" value="{{ $typeValue }}" @checked(old('type', $addressType) === $typeValue)>
                  {{ $typeLabel }}
                </label>
                @endforeach
              </div>
              @error('type')<span class="text-xs text-announce">{{ $message }}</span>@enderror

              <div data-i5="address-form__custom" class="flex flex-col gap-1.5 {{ old('type', $addressType) === 'other' ? '' : 'hidden' }} [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm" id="custom-label-field">
                <label for="address-custom-label">Adres Başlığı *</label>
                <input type="text" id="address-custom-label" name="custom_label" value="{{ old('custom_label', $customLabel) }}" placeholder="Örn: Depo, Şube">
                @error('custom_label')<span class="text-xs text-announce">{{ $message }}</span>@enderror
              </div>

              <div class="grid gap-4 min-[640px]:grid-cols-2" data-i5="address-form__grid">
                <div data-i5="address-form__grid--full" class="flex flex-col gap-1.5 min-[640px]:col-span-full [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_input]:px-3.5 [&_input]:py-[13px] [&_input]:border-[3px] [&_input]:border-ink [&_input]:text-[15px] [&_input]:bg-surface [&_input]:outline-none focus:[&_input]:shadow-brutal-sm [&_textarea]:px-3.5 [&_textarea]:py-[13px] [&_textarea]:border-[3px] [&_textarea]:border-ink [&_textarea]:text-[15px] [&_textarea]:bg-surface [&_textarea]:outline-none focus:[&_textarea]:shadow-brutal-sm [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm" data-i5="address-field">
                  <label for="address-content">Adres *</label>
                  <textarea id="address-content" name="content" rows="3" required autocomplete="street-address" placeholder="Mahalle, sokak, bina no, daire">{{ old('content', $address?->content) }}</textarea>
                  @error('content')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm" data-i5="address-field">
                  <label for="address-city">İl *</label>
                  <select id="address-city" name="city_id" required>
                    <option value="">İl seçin</option>
                    @foreach ($cities as $city)
                    <option value="{{ $city->id }}" @selected((int) old('city_id', $address?->city_id) === $city->id)>{{ $city->name }}</option>
                    @endforeach
                  </select>
                  @error('city_id')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1.5 [&_label]:font-body [&_label]:text-[11px] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[0.06em] [&_select]:px-3.5 [&_select]:py-[13px] [&_select]:border-[3px] [&_select]:border-ink [&_select]:text-[15px] [&_select]:bg-surface [&_select]:outline-none focus:[&_select]:shadow-brutal-sm" data-i5="address-field">
                  <label for="address-county">İlçe *</label>
                  <select id="address-county" name="county_id" required>
                    <option value="">İlçe seçin</option>
                    @foreach ($counties as $county)
                    <option value="{{ $county->id }}" data-city-id="{{ $county->city_id }}" @selected((int) old('county_id', $address?->county_id) === $county->id)>{{ $county->name }}</option>
                    @endforeach
                  </select>
                  @error('county_id')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="flex flex-wrap gap-2.5 pt-1" data-i5="address-form__actions">
                <button data-i5="btn--fill" type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5">{{ $isEdit ? 'Adresi Güncelle' : 'Adresi Kaydet' }}</button>
                <a data-i5="btn--outline" href="{{ route('addressList') }}" class="inline-flex items-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink transition-[transform,box-shadow,background-color] bg-surface text-ink shadow-ui hover:bg-hover">İptal</a>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
<script>
(function () {
  const typeInputs = document.querySelectorAll('input[name="type"]');
  const customField = document.getElementById('custom-label-field');
  const citySelect = document.getElementById('address-city');
  const countySelect = document.getElementById('address-county');
  const allCountyOptions = countySelect ? [...countySelect.querySelectorAll('option[data-city-id]')] : [];

  const toggleCustomLabel = () => {
    const selected = document.querySelector('input[name="type"]:checked');
    if (!customField || !selected) return;
    customField.classList.toggle('hidden', selected.value !== 'other');
  };

  const filterCounties = () => {
    if (!citySelect || !countySelect) return;
    const cityId = citySelect.value;
    const currentCounty = countySelect.value;

    countySelect.innerHTML = '<option value="">İlçe seçin</option>';
    allCountyOptions.forEach((option) => {
      if (!cityId || option.dataset.cityId === cityId) {
        countySelect.appendChild(option.cloneNode(true));
      }
    });

    if (currentCounty && [...countySelect.options].some((opt) => opt.value === currentCounty)) {
      countySelect.value = currentCounty;
    }
  };

  typeInputs.forEach((input) => input.addEventListener('change', toggleCustomLabel));
  citySelect?.addEventListener('change', filterCounties);

  toggleCustomLabel();
  filterCounties();
})();
</script>
@endpush
