@extends('user.layout')
@section('title','Ödeme')
@section('metaRobots', 'noindex,nofollow')
@section('content')
@php
  use App\Enums\AddressScope;
  use App\Enums\InvoiceType;

  $placeholder = asset('user/assets/foto5.jpeg');
  $selectedAddressId = (int) old('address_id', $addresses->first()?->id);
  $invoiceType = old('invoice_type', InvoiceType::INDIVIDUAL->value);
  $selectedAddress = $addresses->firstWhere('id', $selectedAddressId) ?? $addresses->first();
  $isInternationalCheckout = $selectedAddress?->scope === AddressScope::INTERNATIONAL;
@endphp
<main class="pt-8 pb-20">
    <div class="w-full max-w-site mx-auto px-5 lg:px-8" data-i5="container">
      <nav class="flex flex-wrap items-center gap-2 font-body text-xs font-semibold tracking-[0.08em] uppercase text-muted mb-5 [&_a]:text-muted [&_a]:transition-colors [&_a:hover]:text-accent" aria-label="Konum" data-i5="breadcrumb">
        <a href="{{ route('index') }}">Anasayfa</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <a href="{{ route('cart') }}">Sepet</a>
        <span class="opacity-[0.4]" data-i5="breadcrumb__sep">/</span>
        <span>Ödeme</span>
      </nav>

      <div class="mb-8 [&_h1]:font-heading [&_h1]:text-page-title [&_h1]:font-semibold [&_h1]:leading-[1.12] [&_h1]:tracking-[-0.02em] [&_h1]:normal-case" data-i5="checkout-page__head">
        <h1>Ödeme</h1>
        <p class="mt-2.5 text-sm text-muted font-semibold">Teslimat adresinizi seçin ve ödemeyi tamamlayın</p>
      </div>

      @if (session('error'))
      <div class="mb-5 p-3.5 border-[3px] border-ink bg-bg text-sm font-semibold text-announce" role="alert">{{ session('error') }}</div>
      @endif

      <form id="checkout-form" action="{{ route('checkoutStore') }}" method="post" enctype="multipart/form-data" class="grid gap-10 min-[960px]:grid-cols-[1fr_380px] min-[960px]:gap-12 min-[960px]:items-start">
        @csrf

        <div class="grid gap-6">
          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Teslimat Adresi</h2>
            </div>
            <div class="p-5 grid gap-3">
              @foreach ($addresses as $address)
              <label class="flex gap-4 p-4 border-[3px] border-ink cursor-pointer transition-[background,box-shadow] hover:bg-hover has-[:checked]:border-accent has-[:checked]:shadow-brutal-sm" data-i5="checkout-address">
                <input type="radio" name="address_id" value="{{ $address->id }}" class="mt-1 accent-accent checkout-address-input" data-scope="{{ $address->scope->value }}" @checked($selectedAddressId === $address->id) required>
                <div class="flex-1 text-sm leading-relaxed">
                  <p class="font-body text-xs font-bold uppercase tracking-[0.06em] mb-1">{{ $address->title }}</p>
                  <p class="font-semibold text-ink mb-1">{{ $user->name }}</p>
                  <p class="text-muted">{{ $address->content }}</p>
                  <p class="text-muted">{{ $address->formattedLocation() }}</p>
                  <p class="text-[11px] font-bold uppercase tracking-[0.06em] text-accent">{{ $address->scope_label }}</p>
                  @if ($user->phone)
                  <p class="mt-2 font-semibold text-ink">{{ $user->phone }}</p>
                  @endif
                </div>
              </label>
              @endforeach
              @error('address_id')<span class="text-xs text-announce">{{ $message }}</span>@enderror
              <a href="{{ route('addressCreatePage') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-accent underline underline-offset-[3px] hover:text-ink">+ Yeni adres ekle</a>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Fatura Bilgileri</h2>
            </div>
            <div class="p-5 grid gap-5">
              <div>
                <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-2.5">Fatura Tipi *</p>
                <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="Fatura tipi">
                  @foreach (InvoiceType::cases() as $typeCase)
                  <label class="flex items-center gap-1.5 px-3.5 py-2.5 border-[3px] border-ink shadow-brutal-sm font-body text-[11px] font-bold uppercase tracking-[0.06em] cursor-pointer transition-colors has-[:checked]:bg-action has-[:checked]:text-on-dark has-[:checked]:border-ink [&_input]:absolute [&_input]:opacity-0 [&_input]:pointer-events-none">
                    <input type="radio" name="invoice_type" value="{{ $typeCase->value }}" @checked($invoiceType === $typeCase->value)>
                    {{ $typeCase->label() }}
                  </label>
                  @endforeach
                </div>
                @error('invoice_type')<span class="text-xs text-announce">{{ $message }}</span>@enderror
              </div>

              <div id="invoice-individual-fields" class="grid gap-4 {{ $invoiceType === InvoiceType::INDIVIDUAL->value ? '' : 'hidden' }}">
                <div>
                  <label for="checkout-tc-no" class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">T.C. Kimlik Numarası *</label>
                  <input type="text" id="checkout-tc-no" name="tc_no" value="{{ old('tc_no') }}" inputmode="numeric" maxlength="11" placeholder="11 haneli T.C. kimlik no" data-individual-required autocomplete="off"
                         class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[15px] bg-surface outline-none focus:shadow-brutal-sm">
                  <p id="checkout-tc-no-feedback" class="mt-1.5 text-xs text-announce hidden" role="alert" aria-live="polite"></p>
                  @error('tc_no')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
              </div>

              <div id="invoice-corporate-fields" class="grid gap-4 min-[640px]:grid-cols-2 {{ $invoiceType === InvoiceType::CORPORATE->value ? '' : 'hidden' }}">
                <div class="min-[640px]:col-span-2">
                  <label for="checkout-company-name" class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">Şirket Adı *</label>
                  <input type="text" id="checkout-company-name" name="company_name" value="{{ old('company_name') }}" placeholder="Firma / unvan" data-corporate-required
                         class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[15px] bg-surface outline-none focus:shadow-brutal-sm">
                  @error('company_name')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
                <div class="min-[640px]:col-span-2">
                  <label for="checkout-tax-number" class="block font-body text-[11px] font-bold uppercase tracking-[0.06em] mb-1.5">Vergi Numarası *</label>
                  <input type="text" id="checkout-tax-number" name="tax_number" value="{{ old('tax_number') }}" inputmode="numeric" maxlength="11" placeholder="10 veya 11 haneli vergi no" data-corporate-required autocomplete="off"
                         class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[15px] bg-surface outline-none focus:shadow-brutal-sm">
                  <p id="checkout-tax-number-feedback" class="mt-1.5 text-xs text-announce hidden" role="alert" aria-live="polite"></p>
                  @error('tax_number')<span class="text-xs text-announce">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Sipariş Notu</h2>
            </div>
            <div class="p-5">
              <textarea name="note" rows="3" class="w-full px-3.5 py-[13px] border-[3px] border-ink text-[15px] bg-surface outline-none focus:shadow-brutal-sm" placeholder="Teslimat veya baskı ile ilgili notunuz (opsiyonel)">{{ old('note') }}</textarea>
              @error('note')<span class="text-xs text-announce">{{ $message }}</span>@enderror
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Baskı Dosyaları <span class="font-normal normal-case tracking-normal text-muted">(opsiyonel)</span></h2>
            </div>
            <div class="p-5 grid gap-3">
              <p class="text-xs text-muted leading-relaxed">
                İsterseniz baskı dosyanızı buradan yükleyin. En fazla 1 dosya, en fazla 200MB.
                Desteklenen: .png, .pdf, .psd
              </p>
              <input
                type="file"
                id="order_files"
                name="order_files[]"
                accept=".png,.pdf,.psd,image/png,application/pdf"
                class="w-full text-sm file:mr-4 file:px-4 file:py-2 file:border-[3px] file:border-ink file:bg-bg file:font-body file:text-[11px] file:font-bold file:uppercase file:tracking-[0.06em] file:cursor-pointer"
              >
              @error('order_files')<span class="text-xs text-announce">{{ $message }}</span>@enderror
              @error('order_files.*')<span class="text-xs text-announce">{{ $message }}</span>@enderror
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Tasarım Tercihi</h2>
            </div>
            <div class="p-5 grid gap-3">
              <p class="text-xs text-muted leading-relaxed m-0">
                Ödemeye geçmeden önce tasarım sürecinizi seçin. Sözleşmeler için
                <a href="{{ route('agreements') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-accent underline underline-offset-2">Mesafeli Satış / İade</a>
                sayfasını inceleyebilirsiniz.
              </p>
              @php
                $selectedDesignType = old('design_type', \App\Enums\OrderDesignType::FROM_SCRATCH->value);
              @endphp
              @foreach (\App\Enums\OrderDesignType::cases() as $designType)
                <label class="flex items-start gap-3 p-4 border-[3px] border-ink bg-bg cursor-pointer has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                  <input type="radio" name="design_type" value="{{ $designType->value }}" class="mt-1 accent-accent"
                         @checked($selectedDesignType === $designType->value) required>
                  <span>
                    <span class="block font-body text-sm font-bold text-ink">{{ $designType->label() }}</span>
                  </span>
                </label>
              @endforeach
              @error('design_type')<span class="text-xs text-announce">{{ $message }}</span>@enderror
            </div>
          </section>

          <section class="border-[3px] border-ink shadow-brutal-sm bg-surface overflow-hidden" data-i5="checkout-section">
            <div class="px-5 py-4 border-b-[3px] border-ink bg-bg [&_h2]:font-body [&_h2]:text-[13px] [&_h2]:font-bold [&_h2]:uppercase [&_h2]:tracking-[0.06em]">
              <h2>Ödeme Yöntemi</h2>
            </div>
            <div class="p-5 grid gap-4">
              <div id="checkout-payment-iyzico" class="grid gap-4 {{ $isInternationalCheckout ? 'hidden' : '' }}">
                <div class="flex items-start gap-4 p-4 border-[3px] border-accent bg-accent/5" data-i5="checkout-payment">
                  <div class="shrink-0 w-10 h-10 flex items-center justify-center border-[3px] border-ink bg-surface font-body text-xs font-bold uppercase">iyz</div>
                  <div>
                    <p class="font-body text-sm font-bold uppercase tracking-[0.04em] mb-1">iyzico ile Güvenli Ödeme</p>
                    <p class="text-xs text-muted leading-relaxed">Yurt içi teslimat adreslerinde iyzico ödeme sayfasına yönlendirilirsiniz.</p>
                  </div>
                </div>
                <div class="p-4 border-[3px] border-dashed border-ink bg-bg text-xs text-muted leading-relaxed">
                  <p class="font-bold text-ink mb-2">Sandbox test kartı</p>
                  <p>Kart No: <strong>5528 7900 0000 0000</strong> · SKT: <strong>12/30</strong> · CVV: <strong>123</strong></p>
                </div>
              </div>

              <div id="checkout-payment-stripe" class="grid gap-4 {{ $isInternationalCheckout ? '' : 'hidden' }}">
                <div class="flex items-start gap-4 p-4 border-[3px] border-accent bg-accent/5" data-i5="checkout-payment">
                  <div class="shrink-0 w-10 h-10 flex items-center justify-center border-[3px] border-ink bg-[#635bff] text-white font-body text-xs font-bold uppercase">str</div>
                  <div>
                    <p class="font-body text-sm font-bold uppercase tracking-[0.04em] mb-1">Stripe ile Güvenli Ödeme</p>
                    <p class="text-xs text-muted leading-relaxed">Yurt dışı teslimat adreslerinde Stripe ödeme sayfasına yönlendirilirsiniz.</p>
                  </div>
                </div>
                <div class="p-4 border-[3px] border-dashed border-ink bg-bg text-xs text-muted leading-relaxed">
                  <p class="font-bold text-ink mb-2">Stripe test kartı</p>
                  <p>Kart No: <strong>4242 4242 4242 4242</strong> · SKT: gelecekte herhangi bir tarih · CVV: herhangi 3 hane</p>
                </div>
              </div>
            </div>
          </section>
        </div>

        <aside class="border-[3px] border-ink shadow-brutal bg-surface p-7 min-[960px]:sticky min-[960px]:top-[calc(var(--spacing-announce)+80px)]" data-i5="checkout-summary">
          <h2 class="font-body text-[1.1rem] font-bold uppercase mb-6 pb-4 border-b-[3px] border-ink">Sipariş Özeti</h2>

          <div class="grid gap-4 mb-6">
            @foreach ($cartItems as $item)
            @php
              $product = $item->product;
              $resolved = $resolvedByCartId[$item->id] ?? ['unit_price' => (float) $product->price, 'lines' => []];
              $unitPrice = (float) ($resolved['unit_price'] ?? $product->price);
              $lineTotal = $unitPrice * $item->quantity;
            @endphp
            <div class="grid grid-cols-[56px_1fr_auto] gap-3 items-center text-sm">
              <div class="border-[3px] border-ink aspect-square overflow-hidden bg-bg [&_img]:w-full [&_img]:h-full [&_img]:object-cover">
                <img src="{{ $product->images->first()?->url ?? $placeholder }}" alt="{{ $product->title }}">
              </div>
              <div>
                <p class="font-semibold leading-snug">{{ $product->title }}</p>
                <p class="text-muted text-xs">{{ $item->quantity }} adet × {{ number_format($unitPrice, 0, ',', '.') }} ₺</p>
                @if (! empty($resolved['lines']))
                  <ul class="mt-1 space-y-0.5 text-[11px] text-muted">
                    @foreach ($resolved['lines'] as $line)
                      <li>{{ $line['group_title'] }}: {{ $line['property_title'] }}</li>
                    @endforeach
                  </ul>
                @endif
              </div>
              <span class="font-bold whitespace-nowrap">{{ number_format($lineTotal, 0, ',', '.') }} ₺</span>
            </div>
            @endforeach
          </div>

          @if ($shippingFree)
          <div class="mb-6 p-4 bg-bg border-[3px] border-ink text-xs font-semibold uppercase tracking-[0.04em] text-accent">Ücretsiz kargo kazandınız!</div>
          @elseif ($shippingRemaining > 0)
          <div class="mb-6 p-4 bg-bg border-[3px] border-ink text-xs font-semibold uppercase tracking-[0.04em]">Ücretsiz kargo için {{ number_format($shippingRemaining, 0, ',', '.') }} ₺ daha ekleyin</div>
          @endif

          <div class="flex justify-between gap-4 text-sm mb-3 text-muted">
            <span>Ara Toplam</span>
            <span>{{ number_format($subtotal, 0, ',', '.') }} ₺</span>
          </div>
          @if ($discountApplied)
          <div class="flex justify-between gap-4 text-sm mb-3 text-accent">
            <span>İndirim</span>
            <span>-{{ number_format($discountAmount, 0, ',', '.') }} ₺</span>
          </div>
          @endif
          <div class="flex justify-between gap-4 text-sm mb-3 text-muted">
            <span>Kargo</span>
            <span>{{ $shippingFree ? 'Ücretsiz' : number_format($shippingCost, 0, ',', '.').' ₺' }}</span>
          </div>
          <div class="flex justify-between gap-4 font-body text-xl font-bold mt-4 pt-4 border-t-[3px] border-ink">
            <span>Toplam</span>
            <span>{{ number_format($total, 0, ',', '.') }} ₺</span>
          </div>

          <button type="submit" id="checkout-submit" data-i5="checkout-submit" class="mt-6 w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-action text-on-dark shadow-brutal hover:bg-action-hover hover:-translate-x-0.5 hover:-translate-y-0.5 transition-[transform,box-shadow,background-color]">
            {{ $isInternationalCheckout ? 'Stripe ile Öde' : 'iyzico ile Öde' }}
          </button>
          <a href="{{ route('cart') }}" class="block text-center mt-4 text-[13px] font-semibold text-muted underline underline-offset-[3px] hover:text-accent">Sepete Dön</a>
        </aside>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
<script>
(function () {
  const invoiceTypeInputs = document.querySelectorAll('input[name="invoice_type"]');
  const individualFields = document.getElementById('invoice-individual-fields');
  const corporateFields = document.getElementById('invoice-corporate-fields');

  const toggleInvoiceFields = () => {
    const selected = document.querySelector('input[name="invoice_type"]:checked');
    const isIndividual = !selected || selected.value === 'individual';

    individualFields?.classList.toggle('hidden', !isIndividual);
    corporateFields?.classList.toggle('hidden', isIndividual);

    document.querySelectorAll('[data-individual-required]').forEach((el) => {
      el.required = isIndividual;
      el.disabled = !isIndividual;
    });

    document.querySelectorAll('[data-corporate-required]').forEach((el) => {
      el.required = !isIndividual;
      el.disabled = isIndividual;
    });
  };

  invoiceTypeInputs.forEach((input) => input.addEventListener('change', () => {
    toggleInvoiceFields();
    validateTcField(true);
    validateTaxField(true);
  }));
  toggleInvoiceFields();

  const tcInput = document.getElementById('checkout-tc-no');
  const taxInput = document.getElementById('checkout-tax-number');
  const tcFeedback = document.getElementById('checkout-tc-no-feedback');
  const taxFeedback = document.getElementById('checkout-tax-number-feedback');
  const checkoutForm = document.getElementById('checkout-form');

  const onlyDigits = (value) => String(value || '').replace(/\D+/g, '');

  const isValidTurkishIdentityNumber = (value) => {
    const tc = onlyDigits(value);

    if (!/^[1-9][0-9]{10}$/.test(tc)) {
      return false;
    }

    const digits = tc.split('').map(Number);
    const oddSum = digits[0] + digits[2] + digits[4] + digits[6] + digits[8];
    const evenSum = digits[1] + digits[3] + digits[5] + digits[7];

    if (((oddSum * 7) - evenSum) % 10 !== digits[9]) {
      return false;
    }

    return digits.slice(0, 10).reduce((sum, digit) => sum + digit, 0) % 10 === digits[10];
  };

  const isValidTaxNumber = (value) => /^[0-9]{10,11}$/.test(onlyDigits(value));

  const setFieldFeedback = (input, feedback, message) => {
    if (!input || !feedback) {
      return;
    }

    if (message) {
      feedback.textContent = message;
      feedback.classList.remove('hidden');
      input.classList.add('border-announce');
      input.setAttribute('aria-invalid', 'true');
      return;
    }

    feedback.textContent = '';
    feedback.classList.add('hidden');
    input.classList.remove('border-announce');
    input.removeAttribute('aria-invalid');
  };

  const validateTcField = (showEmptyError = false) => {
    if (!tcInput || tcInput.disabled) {
      setFieldFeedback(tcInput, tcFeedback, null);
      return true;
    }

    const value = onlyDigits(tcInput.value);

    if (value === '') {
      if (showEmptyError) {
        setFieldFeedback(tcInput, tcFeedback, 'T.C. kimlik numarası zorunludur.');
        return false;
      }

      setFieldFeedback(tcInput, tcFeedback, null);
      return false;
    }

    if (value.length < 11) {
      setFieldFeedback(tcInput, tcFeedback, 'T.C. kimlik numarası 11 haneli olmalıdır.');
      return false;
    }

    if (!isValidTurkishIdentityNumber(value)) {
      setFieldFeedback(tcInput, tcFeedback, 'Geçerli bir T.C. kimlik numarası girin.');
      return false;
    }

    setFieldFeedback(tcInput, tcFeedback, null);
    return true;
  };

  const validateTaxField = (showEmptyError = false) => {
    if (!taxInput || taxInput.disabled) {
      setFieldFeedback(taxInput, taxFeedback, null);
      return true;
    }

    const value = onlyDigits(taxInput.value);

    if (value === '') {
      if (showEmptyError) {
        setFieldFeedback(taxInput, taxFeedback, 'Vergi numarası zorunludur.');
        return false;
      }

      setFieldFeedback(taxInput, taxFeedback, null);
      return false;
    }

    if (value.length < 10) {
      setFieldFeedback(taxInput, taxFeedback, 'Vergi numarası 10 veya 11 haneli olmalıdır.');
      return false;
    }

    if (!isValidTaxNumber(value)) {
      setFieldFeedback(taxInput, taxFeedback, 'Vergi numarası 10 veya 11 haneli olmalıdır.');
      return false;
    }

    setFieldFeedback(taxInput, taxFeedback, null);
    return true;
  };

  const bindNumericInvoiceInput = (input, validateFn) => {
    if (!input) {
      return;
    }

    input.addEventListener('input', () => {
      const digits = onlyDigits(input.value);
      if (input.value !== digits) {
        input.value = digits;
      }
      validateFn(false);
    });

    input.addEventListener('blur', () => validateFn(true));
  };

  bindNumericInvoiceInput(tcInput, validateTcField);
  bindNumericInvoiceInput(taxInput, validateTaxField);

  checkoutForm?.addEventListener('submit', (event) => {
    const tcValid = validateTcField(true);
    const taxValid = validateTaxField(true);

    if (!tcValid || !taxValid) {
      event.preventDefault();
      const firstInvalid = (!tcValid && tcInput && !tcInput.disabled) ? tcInput : taxInput;
      firstInvalid?.focus();
      firstInvalid?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  validateTcField(Boolean(tcInput?.value));
  validateTaxField(Boolean(taxInput?.value));

  const addressInputs = document.querySelectorAll('.checkout-address-input');
  const paymentIyzico = document.getElementById('checkout-payment-iyzico');
  const paymentStripe = document.getElementById('checkout-payment-stripe');
  const submitButton = document.getElementById('checkout-submit');

  const togglePaymentMethod = () => {
    const selected = document.querySelector('.checkout-address-input:checked');
    const isInternational = selected?.dataset.scope === 'international';

    paymentIyzico?.classList.toggle('hidden', !!isInternational);
    paymentStripe?.classList.toggle('hidden', !isInternational);

    if (submitButton) {
      submitButton.textContent = isInternational ? 'Stripe ile Öde' : 'iyzico ile Öde';
    }
  };

  addressInputs.forEach((input) => input.addEventListener('change', togglePaymentMethod));
  togglePaymentMethod();

  const orderFilesInput = document.getElementById('order_files');
  orderFilesInput?.addEventListener('change', () => {
    if (!orderFilesInput.files) {
      return;
    }

    if (orderFilesInput.files.length > 1) {
      alert('En fazla 1 dosya yükleyebilirsiniz.');
      orderFilesInput.value = '';
      return;
    }

    const maxBytes = 200 * 1024 * 1024;
    const allowed = ['png', 'pdf', 'psd'];

    for (const file of orderFilesInput.files) {
      const ext = (file.name.split('.').pop() || '').toLowerCase();

      if (!allowed.includes(ext)) {
        alert('Desteklenmeyen dosya: ' + file.name);
        orderFilesInput.value = '';
        return;
      }

      if (file.size > maxBytes) {
        alert('Dosya 200MB sınırını aşıyor: ' + file.name);
        orderFilesInput.value = '';
        return;
      }
    }
  });
})();
</script>
@endpush
