/**
 * addresses.js — Adres sayfaları (addresses.html, address-new.html)
 *
 * NE YAPAR:
 *   Varsayılan adres seçimi (is-default + localStorage), adres formu, düzenleme modu.
 *
 * NE YAPMAZ:
 *   Adres kaydetme/silme API'si. Form submit sadece addresses.html'e yönlendirir.
 *   Kartlar HTML'de statik; JS yalnızca varsayılan rozeti ve buton görünürlüğünü yönetir.
 */

/** localStorage anahtarı — checkout.js demo adres id'leri ile uyumlu (home/work) */
const PMP_DEFAULT_ADDRESS_KEY = 'pmp-default-address-id';
const ADDRESS_DEFAULT_BADGE_CLASS =
  'font-body text-[9px] font-bold uppercase tracking-[0.06em] px-2 py-1 bg-accent text-on-dark border-2 border-accent';

document.addEventListener('DOMContentLoaded', () => {
  initAddressTypeToggle();
  initAddressEditMode();
  initAddressesList();
  initAddressFormSubmit();
});

/** addresses.html — "Varsayılan Yap" tıklanınca is-default taşır */
function initAddressesList() {
  if (document.body.dataset.page !== 'addresses') return;

  const grid = document.getElementById('addresses-grid');
  if (!grid) return;

  const cards = [...grid.querySelectorAll('[data-i5="address-card"]')];
  cards.forEach((card) => syncAddressCardDefaultUI(card));

  const savedId = localStorage.getItem(PMP_DEFAULT_ADDRESS_KEY);
  if (savedId) {
    const savedCard = grid.querySelector(`[data-address-id="${savedId}"]`);
    if (savedCard) setDefaultAddressCard(savedCard, { persist: false });
  }

  grid.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-i5="address-card__btn--primary"]');
    if (!btn) return;

    const card = btn.closest('[data-i5="address-card"]');
    if (!card || card.classList.contains('is-default')) return;

    setDefaultAddressCard(card);
  });
}

/** Tek karta varsayılan verir; diğer kartlardan is-default kaldırır */
function setDefaultAddressCard(card, { persist = true } = {}) {
  const grid = document.getElementById('addresses-grid');
  if (!grid || !card) return;

  grid.querySelectorAll('[data-i5="address-card"]').forEach((item) => {
    const isDefault = item === card;
    item.classList.toggle('is-default', isDefault);
    syncAddressCardDefaultUI(item, isDefault);
  });

  if (persist && card.dataset.addressId) {
    localStorage.setItem(PMP_DEFAULT_ADDRESS_KEY, card.dataset.addressId);
  }
}

/** Varsayılan rozeti ve "Varsayılan Yap" butonunun hidden durumunu senkronize eder */
function syncAddressCardDefaultUI(card, isDefault = card.classList.contains('is-default')) {
  const badges = card.querySelector('[data-i5="address-card__badges"]');
  const primaryBtn = card.querySelector('[data-i5="address-card__btn--primary"]');

  if (badges) {
    let badge = badges.querySelector('[data-i5="address-card__default"]');
    if (isDefault && !badge) {
      badge = document.createElement('span');
      badge.className = ADDRESS_DEFAULT_BADGE_CLASS;
      badge.dataset.i5 = 'address-card__default';
      badge.textContent = 'Varsayılan';
      badges.appendChild(badge);
    } else if (!isDefault && badge) {
      badge.remove();
    }
  }

  if (primaryBtn) {
    primaryBtn.hidden = isDefault;
    primaryBtn.classList.toggle('hidden', isDefault);
  }
}

/** address-new.html — submit engellenir, addresses.html'e yönlendirilir (API yok) */
function initAddressFormSubmit() {
  const form = document.getElementById('address-form');
  if (!form) return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const isDefault = Boolean(form.querySelector('[name="is_default"]')?.checked);
    if (isDefault) {
      const editId = new URLSearchParams(window.location.search).get('edit');
      localStorage.setItem(PMP_DEFAULT_ADDRESS_KEY, editId || 'home');
    }

    window.location.href = 'addresses.html';
  });
}

/** "Diğer" adres tipi seçilince özel etiket alanı gösterilir */
function initAddressTypeToggle() {
  const form = document.getElementById('address-form');
  const customField = document.getElementById('custom-label-field');
  const customInput = document.getElementById('address-custom-label');

  if (!form || !customField) return;

  const sync = () => {
    const type = form.querySelector('input[name="type"]:checked')?.value;
    const isOther = type === 'other';
    customField.classList.toggle('hidden', !isOther);
    if (customInput) {
      customInput.required = isOther;
      if (!isOther) customInput.value = '';
    }
  };

  form.querySelectorAll('input[name="type"]').forEach((input) => {
    input.addEventListener('change', sync);
  });

  sync();
}

/** ?edit=home|work — demo veriyle formu doldurur (gerçek GET API yok) */
function initAddressEditMode() {
  const params = new URLSearchParams(window.location.search);
  const editId = params.get('edit');
  if (!editId) return;

  const demos = {
    home: {
      type: 'home',
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
    work: {
      type: 'work',
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
  };

  const data = demos[editId];
  if (!data) return;

  const form = document.getElementById('address-form');
  if (!form) return;

  document.title = 'Adres Düzenle — PureMatPrint';
  document.getElementById('address-page-title')?.replaceChildren(document.createTextNode('Adres Düzenle'));
  document.getElementById('address-page-crumb')?.replaceChildren(document.createTextNode('Adres Düzenle'));

  const typeInput = form.querySelector(`input[name="type"][value="${data.type}"]`);
  if (typeInput) typeInput.checked = true;

  Object.entries(data).forEach(([key, value]) => {
    if (key === 'type' || key === 'is_default') return;
    const el = form.querySelector(`[name="${key}"]`);
    if (el) el.value = value;
  });

  const defaultCheck = form.querySelector('[name="is_default"]');
  if (defaultCheck) defaultCheck.checked = data.is_default;

  form.querySelector('input[name="type"]:checked')?.dispatchEvent(new Event('change'));
}
