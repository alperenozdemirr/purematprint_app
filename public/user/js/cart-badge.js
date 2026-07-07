/**
 * cart-badge.js — Header sepet rozeti (tüm sayfalar)
 *
 * NE YAPAR:
 *   Header'daki sepet ikonuna adet rozeti ekler/günceller.
 *
 * NE YAPMAZ:
 *   Sepet içeriğini saklamaz — sadece adet sayısı (pmp-cart-qty).
 *   cart.html dışında demo değer 3 gösterir; cart sayfasında gerçek adet hesaplanır.
 */

const I5_CART_QTY_KEY = 'pmp-cart-qty';
/** localStorage boşsa gösterilecek demo adet */
const I5_CART_QTY_DEFAULT = 3;

/** Global API — cart.js syncFromCartPage() ile çağrılır */
const I5CartBadge = {
  get() {
    try {
      const stored = localStorage.getItem(I5_CART_QTY_KEY);
      if (stored !== null) return Math.max(0, Number(stored) || 0);
    } catch (_) {
      /* localStorage kullanılamıyorsa demo değeri */
    }
    return I5_CART_QTY_DEFAULT;
  },

  set(qty) {
    const count = Math.max(0, Math.min(999, Math.round(Number(qty) || 0)));
    try {
      localStorage.setItem(I5_CART_QTY_KEY, String(count));
    } catch (_) {
      /* ignore */
    }
    I5CartBadge.render(count);
    return count;
  },

  syncFromCartPage() {
    const items = document.querySelectorAll('#cart-items [data-i5="cart-item"]');
    if (!items.length) return I5CartBadge.set(0);

    let total = 0;
    items.forEach((item) => {
      total += Number(item.querySelector('[data-i5="cart-qty"] input')?.value) || 1;
    });
    return I5CartBadge.set(total);
  },

  ensureBadges() {
    document.querySelectorAll('[data-i5="header__cart"]').forEach((link) => {
      if (link.querySelector('[data-cart-badge]')) return;
      const badge = document.createElement('span');
      badge.className = window.TW?.header__cart_count || '';
      badge.dataset.cartBadge = '';
      badge.hidden = true;
      link.appendChild(badge);
    });
  },

  render(qty = I5CartBadge.get()) {
    I5CartBadge.ensureBadges();

    document.querySelectorAll('[data-cart-badge]').forEach((badge) => {
      const link = badge.closest('[data-i5="header__cart"]');
      if (qty > 0) {
        badge.textContent = qty > 99 ? '99+' : String(qty);
        badge.hidden = false;
        badge.setAttribute('aria-hidden', 'false');
        if (link) link.setAttribute('aria-label', `Sepet (${qty} ürün)`);
      } else {
        badge.hidden = true;
        badge.setAttribute('aria-hidden', 'true');
        if (link) link.setAttribute('aria-label', 'Sepet');
      }
    });
  },

  init() {
    if (!document.body?.hasAttribute('data-pmp-site')) return;
    I5CartBadge.ensureBadges();
    if (document.getElementById('cart-items')) {
      I5CartBadge.syncFromCartPage();
    } else {
      I5CartBadge.render();
    }
  },
};

window.I5CartBadge = I5CartBadge;

document.addEventListener('DOMContentLoaded', () => I5CartBadge.init());
