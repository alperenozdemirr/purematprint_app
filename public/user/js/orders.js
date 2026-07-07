/**
 * orders.js — Siparişlerim sayfası (orders.html)
 *
 * NE YAPAR:
 *   Durum filtreleri — uymayan kartlara is-filter-hidden ekler.
 *
 * NE YAPMAZ:
 *   Sipariş verisi çekmez; kartlar HTML'de statik, data-order-status ile işaretli.
 */

(function initOrdersFilters() {
  const filters = document.querySelector('[data-i5="orders-filters"]');
  const list = document.querySelector('[data-i5="orders-list"]');
  const empty = document.getElementById('orders-empty');
  if (!filters || !list) return;

  const cards = [...list.querySelectorAll('[data-i5="order-card"]')];
  const buttons = [...filters.querySelectorAll('[data-i5="orders-filter"][data-filter]')];

  /** "active" = pending + processing + shipped birleşik filtre */
  const ACTIVE_STATUSES = new Set(['pending', 'processing', 'shipped']);

  const matchesFilter = (status, filter) => {
    if (filter === 'all') return true;
    if (filter === 'active') return ACTIVE_STATUSES.has(status);
    if (filter === 'delivered') return status === 'delivered';
    if (filter === 'cancelled') return status === 'cancelled';
    return true;
  };

  const applyFilter = (filter) => {
    let visible = 0;

    cards.forEach((card) => {
      const status = card.dataset.orderStatus || '';
      const show = matchesFilter(status, filter);
      card.classList.toggle('is-filter-hidden', !show);
      if (show) visible += 1;
    });

    buttons.forEach((btn) => {
      const isActive = btn.dataset.filter === filter;
      btn.classList.toggle('is-active', isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    if (empty) {
      empty.classList.toggle('hidden', visible > 0);
      list.classList.toggle('hidden', visible === 0);
    }
  };

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => applyFilter(btn.dataset.filter || 'all'));
  });

  const initialFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
  applyFilter(initialFilter);
})();
