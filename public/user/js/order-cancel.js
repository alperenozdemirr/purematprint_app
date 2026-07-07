/**
 * order-cancel.js — Sipariş iptal sayfası (order-cancel.html)
 *
 * NE YAPAR:
 *   ?id= parametresine göre demo sipariş bilgisi doldurur, iptal formu gösterir.
 *
 * NE YAPMAZ:
 *   Gerçek iptal API'si — submit sonrası yalnızca başarı mesajı gösterilir.
 */

/** Demo siparişler — orders.html'deki id'lerle eşleşmeli */
const PMP_DEMO_ORDERS = {
  'PMP-DEMO005': {
    product: 'Premium Kartvizit',
    meta: '2 adet · Kredi / Banka Kartı',
    date: '7 Temmuz 2026 10:45',
    total: '1.120 ₺',
    status: 'Yeni Sipariş',
    thumb: 'assets/foto5.jpeg',
    cancellable: true,
  },
  'PMP-DEMO002': {
    product: 'Roll-Up Banner',
    meta: '1 adet · Havale / EFT',
    date: '31 Mayıs 2026 10:15',
    total: '979 ₺',
    status: 'Üretimde',
    thumb: 'assets/foto1.jpeg',
    cancellable: true,
  },
};

document.addEventListener('DOMContentLoaded', initOrderCancel);

/** Sayfa yüklenince URL'deki sipariş id'sine göre form veya hata durumu */
function initOrderCancel() {
  if (document.body.dataset.page !== 'order-cancel') return;

  const params = new URLSearchParams(window.location.search);
  const orderId = params.get('id') || 'PMP-DEMO005';
  const order = PMP_DEMO_ORDERS[orderId];

  const formWrap = document.getElementById('order-cancel-form-wrap');
  const layout = document.querySelector('[data-i5="order-cancel__layout"]');
  const form = document.getElementById('order-cancel-form');
  const success = document.getElementById('order-cancel-success');
  const head = document.querySelector('[data-i5="order-cancel__head"]');

  if (!order) {
    if (head) {
      head.querySelector('h1').textContent = 'Sipariş Bulunamadı';
      head.querySelector('[data-i5="order-cancel__sub"]').textContent = 'Bu sipariş iptal edilemez veya mevcut değil.';
    }
    formWrap?.classList.add('hidden');
    document.querySelector('[data-i5="order-cancel-summary"]')?.classList.add('hidden');
    return;
  }

  document.getElementById('order-cancel-id')?.replaceChildren(document.createTextNode(orderId));
  document.getElementById('order-cancel-product')?.replaceChildren(document.createTextNode(order.product));
  document.getElementById('order-cancel-meta')?.replaceChildren(document.createTextNode(order.meta));
  document.getElementById('order-cancel-date')?.replaceChildren(document.createTextNode(order.date));
  document.getElementById('order-cancel-total')?.replaceChildren(document.createTextNode(order.total));
  document.getElementById('order-cancel-status')?.replaceChildren(document.createTextNode(order.status));

  const thumb = document.getElementById('order-cancel-thumb');
  if (thumb) {
    thumb.src = order.thumb;
    thumb.alt = order.product;
  }

  if (!order.cancellable) {
    formWrap?.classList.add('hidden');
    return;
  }

  form?.addEventListener('submit', (event) => {
    event.preventDefault();

    layout?.classList.add('hidden');
    head?.classList.add('hidden');
    success?.classList.remove('hidden');

    const successText = document.getElementById('order-cancel-success-text');
    if (successText) {
      successText.textContent = `${orderId} numaralı siparişiniz iptal edildi. Ödemeniz 3–5 iş günü içinde iade edilecektir.`;
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
