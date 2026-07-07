/**
 * categories.js — Kategoriler sayfası (categories.html)
 *
 * Standart scroll-reveal yerine kartlara soldan kayarak giren
 * kademeli (stagger) animasyon uygular — is-cat-in sınıfı.
 */

document.addEventListener('DOMContentLoaded', initCategoriesCards);

function initCategoriesCards() {
  if (document.body.dataset.page !== 'categories') return;

  const cards = [...document.querySelectorAll('[data-i5-tags~="cat-index__card"]')];
  if (!cards.length) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    cards.forEach((card) => card.classList.add('is-cat-in'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const card = entry.target;
        const index = cards.indexOf(card);
        window.setTimeout(() => {
          card.classList.add('is-cat-in');
        }, Math.max(0, index) * 100);
        observer.unobserve(card);
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
  );

  cards.forEach((card) => observer.observe(card));
}
