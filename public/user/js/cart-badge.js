/**
 * cart-badge.js — Header sepet rozeti (tüm sayfalar)
 *
 * NE YAPAR:
 *   Header'daki sepet ikonuna adet rozeti ekler/günceller.
 *
 * NE YAPMAZ:
 *   Sepet içeriğini saklamaz — adet sunucudan data-cart-count ile gelir.
 */

/** Global API */
const I5CartBadge = {
  get() {
    const count = Number(document.body?.dataset.cartCount);
    if (!Number.isNaN(count)) {
      return Math.max(0, count);
    }

    return 0;
  },

  set(qty) {
    const count = Math.max(0, Math.min(999, Math.round(Number(qty) || 0)));

    if (document.body) {
      document.body.dataset.cartCount = String(count);
    }

    I5CartBadge.render(count);
    return count;
  },

  syncFromCartPage() {
    const countEl = document.querySelector('[data-i5="cart-page__count"]');
    if (!countEl) return I5CartBadge.render();

    const match = countEl.textContent?.match(/(\d+)/);
    return I5CartBadge.set(match ? Number(match[1]) : 0);
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
