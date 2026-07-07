/**
 * checkout.js — Ödeme sayfası (checkout.html)
 *
 * NE YAPAR:
 *   Demo adres kartı gösterir, fatura adresi toggle, ödeme yöntemi seçimi,
 *   form gönderimi → toast → order-success.html yönlendirme.
 *
 * NE YAPMAZ:
 *   Gerçek ödeme, 3D Secure, adres API'si. Adresler PMP_DEMO_ADDRESSES sabitinde.
 *   ?no-address=1 ile adres yok senaryosu test edilebilir.
 */

/** Demo kullanıcı — checkout formunda e-posta alanına yazılır */
const PMP_DEMO_USER = {
  email: 'demo@purematprint.com',
};

/** Sabit demo adresler — backend'den gelmez; addresses.js ile aynı id'ler (home/work) */
const PMP_DEMO_ADDRESSES = [
  {
    id: 'home',
    label: 'Ev',
    firstname: 'Demo',
    lastname: 'Kullanıcı',
    company: '',
    phone: '0532 000 00 00',
    postal: '34710',
    address: 'Örnek Mah. Baskı Sk. No:12 D:4',
    city: 'İstanbul',
    district: 'Kadıköy',
    is_default: true,
  },
  {
    id: 'work',
    label: 'İş',
    firstname: 'Demo',
    lastname: 'Kullanıcı',
    company: 'PureMatPrint Ltd.',
    phone: '0212 000 00 00',
    postal: '34394',
    address: 'Levent Plaza, Büyükdere Cd. No:100',
    city: 'İstanbul',
    district: 'Şişli',
    is_default: false,
  },
];

document.addEventListener('DOMContentLoaded', initCheckout);

function initCheckout() {
  if (document.body.dataset.page !== 'checkout') return;

  initShippingAddress();
  initBillingToggle();
  initPaymentMethods();
  initCheckoutSubmit();
}

/** URL ?no-address=1 ise boş adres listesi döner (checkout boş durumu testi) */
function getDemoAddresses() {
  const params = new URLSearchParams(window.location.search);
  if (params.get('no-address') === '1') return [];
  return PMP_DEMO_ADDRESSES;
}

/** Teslimat adresi bölümü — adres yoksa uyarı, varsa varsayılan kartı JS ile oluşturur */
function initShippingAddress() {
  const emptyEl = document.getElementById('checkout-address-empty');
  const savedEl = document.getElementById('checkout-address-saved');
  const manageLink = document.getElementById('checkout-address-manage');
  const hiddenInput = document.getElementById('checkout-shipping-address-id');
  const emailInput = document.getElementById('checkout-email');
  const phoneInput = document.getElementById('checkout-phone');
  const submitBtn = document.querySelector('[data-i5="checkout-submit"]');
  if (!emptyEl || !savedEl) return;

  const addresses = getDemoAddresses();
  const hasAddresses = addresses.length > 0;

  emptyEl.classList.toggle('hidden', hasAddresses);
  savedEl.classList.toggle('hidden', !hasAddresses);
  manageLink?.classList.toggle('hidden', !hasAddresses);

  if (submitBtn) {
    submitBtn.disabled = !hasAddresses;
    submitBtn.classList.toggle('opacity-35', !hasAddresses);
    submitBtn.classList.toggle('cursor-not-allowed', !hasAddresses);
  }

  if (!hasAddresses) {
    if (hiddenInput) hiddenInput.value = '';
    if (emailInput) emailInput.value = '';
    if (phoneInput) phoneInput.value = '';
    return;
  }

  savedEl.replaceChildren();
  const defaultAddress = addresses.find((item) => item.is_default) || addresses[0];

  const setSelectedAddress = (addressId) => {
    if (hiddenInput) hiddenInput.value = addressId;
  };

  const buildAddressBody = (item, title) => {
    const fullName = `${item.firstname} ${item.lastname}`.trim();
    const wrapper = document.createElement('div');
    wrapper.className = 'min-w-0 flex-1 text-sm leading-[1.6]';
    wrapper.dataset.i5 = 'checkout-address__info';

    const head = document.createElement('div');
    head.className = 'flex flex-wrap items-center gap-2 mb-2';

    const strong = document.createElement('strong');
    strong.className = 'font-body text-[13px] font-bold uppercase tracking-[0.04em]';
    strong.textContent = title;
    head.appendChild(strong);

    if (item.is_default) {
      const badge = document.createElement('span');
      badge.className = 'font-body text-[9px] font-bold uppercase tracking-[0.06em] px-2 py-1 bg-accent text-on-dark border-2 border-accent';
      badge.dataset.i5 = 'checkout-address__badge';
      badge.textContent = 'Varsayılan';
      head.appendChild(badge);
    }

    const name = document.createElement('p');
    name.className = 'font-bold text-ink mb-1';
    name.dataset.i5 = 'checkout-address__name';
    name.textContent = fullName;

    wrapper.appendChild(head);
    wrapper.appendChild(name);

    const email = document.createElement('p');
    email.className = 'text-muted';
    email.dataset.i5 = 'checkout-address__email';
    email.textContent = PMP_DEMO_USER.email;
    wrapper.appendChild(email);

    const phone = document.createElement('p');
    phone.className = 'text-[13px] font-semibold text-ink';
    phone.dataset.i5 = 'checkout-address__phone';
    phone.textContent = item.phone;
    wrapper.appendChild(phone);

    const divider = document.createElement('div');
    divider.className = 'my-3 border-t-[2px] border-ink/15';
    wrapper.appendChild(divider);

    [item.company, item.address, `${item.district}, ${item.city} ${item.postal}`.trim()]
      .filter(Boolean)
      .forEach((line) => {
        const p = document.createElement('p');
        p.className = 'text-muted';
        p.dataset.i5 = 'checkout-address__line';
        p.textContent = line;
        wrapper.appendChild(p);
      });

    return wrapper;
  };

  const card = document.createElement('div');
  card.className = 'border-[3px] border-accent shadow-brutal bg-bg p-4';
  card.dataset.i5 = 'checkout-address';
  card.appendChild(buildAddressBody(defaultAddress, 'Kayıtlı Adresim'));

  if (addresses.length > 1) {
    const changeLink = document.createElement('a');
    changeLink.href = 'addresses.html';
    changeLink.className = 'inline-block mt-4 font-body text-[11px] font-bold uppercase tracking-[0.06em] text-accent underline underline-offset-[3px] transition-colors hover:text-accent-dark';
    changeLink.textContent = 'Farklı Adres Seç';
    card.appendChild(changeLink);
  }

  savedEl.appendChild(card);
  setSelectedAddress(defaultAddress.id);
  if (emailInput) emailInput.value = PMP_DEMO_USER.email;
  if (phoneInput) phoneInput.value = defaultAddress.phone;
}

/** "Fatura adresi teslimat ile aynı" checkbox — işaretliyken fatura formu gizlenir */
function initBillingToggle() {
  const checkbox = document.getElementById('checkout-same-billing');
  const billing = document.getElementById('checkout-billing');
  if (!checkbox || !billing) return;

  const sync = () => {
    billing.classList.toggle('hidden', checkbox.checked);
  };

  checkbox.addEventListener('change', sync);
  sync();
}

/** Kart / havale seçimi — seçilen yönteme göre ilgili alanları gösterir */
function initPaymentMethods() {
  const payments = document.querySelectorAll('[data-i5="checkout-payment"]');
  const cardFields = document.getElementById('checkout-card-fields');
  const transferInfo = document.getElementById('checkout-transfer-info');
  if (!payments.length) return;

  const sync = () => {
    const selected = document.querySelector('[data-i5="checkout-payment"] input[type="radio"]:checked');
    const value = selected?.value || 'card';

    payments.forEach((label) => {
      const input = label.querySelector('input[type="radio"]');
      label.classList.toggle('is-active', Boolean(input?.checked));
    });

    if (cardFields) cardFields.classList.toggle('hidden', value !== 'card');
    if (transferInfo) transferInfo.classList.toggle('hidden', value !== 'transfer');
  };

  payments.forEach((label) => {
    const input = label.querySelector('input[type="radio"]');
    if (!input) return;

    input.addEventListener('change', sync);
    label.addEventListener('click', () => {
      if (!input.checked) {
        input.checked = true;
        sync();
      }
    });
  });

  sync();
}

/** Submit — gerçek ödeme yok; toast gösterip 2.2 sn sonra başarı sayfasına gider */
function initCheckoutSubmit() {
  const form = document.getElementById('checkout-form');
  const toast = document.getElementById('checkout-toast');
  const submitBtn = document.querySelector('[data-i5="checkout-submit"]');
  if (!form || !toast) return;

  let isSubmitting = false;

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    if (isSubmitting || submitBtn?.disabled) return;

    const addressId = document.getElementById('checkout-shipping-address-id')?.value;
    if (!addressId) return;

    isSubmitting = true;
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-35', 'cursor-not-allowed');
    }

    toast.classList.add('is-visible');

    window.setTimeout(() => {
      window.location.href = 'order-success.html';
    }, 2200);
  });
}
