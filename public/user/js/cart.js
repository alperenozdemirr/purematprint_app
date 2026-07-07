/**
 * cart.js — Sepet sayfası (cart.html)
 *
 * NE YAPAR:
 *   Adet artır/azalt, ürün sil, ara toplam/kargo/toplam hesapla, boş sepet göster.
 *
 * NE YAPMAZ:
 *   Sepete ürün ekleme (product.html'de statik link), ödeme, API kaydı.
 *   Veri HTML'de statik; localStorage'a sepet içeriği yazmaz (sadece rozet güncellenir).
 */

/** 500₺ altında sabit kargo ücreti */
const I5_FREE_SHIPPING_MIN = 500;
const I5_SHIPPING_FEE = 49;

document.addEventListener('DOMContentLoaded', initCart);

/** #cart-items yoksa (başka sayfadaysak) hiçbir şey yapmaz */
function initCart() {
  const itemsRoot = document.getElementById('cart-items');
  if (!itemsRoot) return;

  itemsRoot.querySelectorAll('[data-i5="cart-item"]').forEach((item) => {
    initCartItemQty(item);
    initCartItemRemove(item);
  });

  updateCartSummary();
}

/** Tek satırdaki +/- butonları ve input — min 1, max 999 */
function initCartItemQty(item) {
  const qtyWrap = item.querySelector('[data-i5="cart-qty"]');
  if (!qtyWrap) return;

  const input = qtyWrap.querySelector('input[type="number"]');
  const buttons = qtyWrap.querySelectorAll('button');
  const minusBtn = buttons[0];
  const plusBtn = buttons[1];
  const totalEl = item.querySelector('[data-i5="cart-item__total"]');
  if (!input || !minusBtn || !plusBtn) return;

  const unitPrice = getUnitPrice(item);

  const syncQty = (raw) => {
    const qty = Math.max(1, Math.min(999, Math.round(Number(raw) || 1)));
    input.value = String(qty);
    minusBtn.disabled = qty <= 1;
    if (totalEl) totalEl.textContent = formatPrice(unitPrice * qty);
    updateCartSummary();
  };

  minusBtn.addEventListener('click', () => syncQty(Number(input.value) - 1));
  plusBtn.addEventListener('click', () => syncQty(Number(input.value) + 1));
  input.addEventListener('change', () => syncQty(input.value));
  input.addEventListener('blur', () => syncQty(input.value));

  syncQty(input.value);
}

/** Sil butonu — satırı DOM'dan kaldırır, özet ve rozeti günceller */
function initCartItemRemove(item) {
  const removeBtn = item.querySelector('[data-i5="cart-item__remove"]');
  if (!removeBtn) return;

  removeBtn.addEventListener('click', () => {
    item.remove();
    updateCartSummary();

    const itemsRoot = document.getElementById('cart-items');
    if (itemsRoot && !itemsRoot.querySelector('[data-i5="cart-item"]')) {
      showEmptyCart(itemsRoot);
    }
  });
}

/** Birim fiyat — data-unit-price veya fiyat metninden parse edilir */
function getUnitPrice(item) {
  if (item.dataset.unitPrice) return Number(item.dataset.unitPrice);

  const priceText = item.querySelector('[data-i5="cart-item__price"]')?.textContent || '';
  const match = priceText.match(/([\d.]+)/);
  return match ? Number(match[1].replace(/\./g, '')) : 0;
}

function formatPrice(amount) {
  return `${Math.round(amount).toLocaleString('tr-TR')} ₺`;
}

/** Özet panelindeki tüm tutarları ve ücretsiz kargo barını yeniden hesaplar */
function updateCartSummary() {
  const items = [...document.querySelectorAll('#cart-items [data-i5="cart-item"]')];
  let subtotal = 0;
  let totalQty = 0;

  items.forEach((item) => {
    const qty = Number(item.querySelector('[data-i5="cart-qty"] input')?.value) || 1;
    subtotal += getUnitPrice(item) * qty;
    totalQty += qty;
  });

  const countEl = document.querySelector('[data-i5="cart-page__count"]');
  if (countEl) countEl.textContent = `${totalQty} ürün`;

  const subtotalEl = document.querySelector('[data-cart-subtotal]');
  const shippingEl = document.querySelector('[data-cart-shipping]');
  const totalEl = document.querySelector('[data-cart-total]');
  const shippingFree = subtotal >= I5_FREE_SHIPPING_MIN;
  const shippingCost = shippingFree ? 0 : I5_SHIPPING_FEE;
  const total = subtotal + shippingCost;

  if (subtotalEl) subtotalEl.textContent = formatPrice(subtotal);
  if (shippingEl) shippingEl.textContent = shippingFree ? 'Ücretsiz' : formatPrice(shippingCost);
  if (totalEl) totalEl.textContent = formatPrice(total);

  updateShippingBar(subtotal, shippingFree);
  window.I5CartBadge?.syncFromCartPage();
}

function updateShippingBar(subtotal, shippingFree) {
  const bar = document.querySelector('[data-i5="cart-shipping-bar"]');
  if (!bar) return;

  const msg = bar.querySelector('p');
  if (!msg) return;

  if (shippingFree) {
    msg.textContent = 'Ücretsiz kargo kazandınız!';
    msg.classList.add('is-free');
    return;
  }

  const remaining = I5_FREE_SHIPPING_MIN - subtotal;
  msg.textContent = `Ücretsiz kargo için ${formatPrice(remaining)} daha ekleyin`;
  msg.classList.remove('is-free');
}

/** Son ürün silinince boş sepet mesajı gösterir — tw-classes.js stilleri kullanır */
function showEmptyCart(itemsRoot) {
  const T = window.TW || {};
  itemsRoot.innerHTML = `
    <div class="${T.cart_empty || ''}" data-i5="cart-empty">
      <svg class="w-12 h-12 mx-auto mb-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
      </svg>
      <h2 class="${T.cart_empty__title || 'font-body text-xl font-bold uppercase mb-2'}">Sepetiniz boş</h2>
      <p class="${T.cart_empty__text || 'text-muted mb-6'}">Henüz sepetinize ürün eklemediniz.</p>
      <a href="products.html" class="${T.btn || ''} ${T.btn__fill || ''}">Alışverişe Başla</a>
    </div>
  `;

  const countEl = document.querySelector('[data-i5="cart-page__count"]');
  if (countEl) countEl.textContent = '0 ürün';

  document.querySelector('[data-cart-subtotal]')?.replaceChildren(document.createTextNode('0 ₺'));
  document.querySelector('[data-cart-shipping]')?.replaceChildren(document.createTextNode('—'));
  document.querySelector('[data-cart-total]')?.replaceChildren(document.createTextNode('0 ₺'));
  window.I5CartBadge?.set(0);
}
