/**
 * collection.js — Koleksiyon sayfası (collection.html)
 *
 * Kartlara alttan scale ile giren kademeli animasyon — is-col-in sınıfı.
 * (categories.html soldan skew kullanır; burada farklı bir giriş efekti)
 */

document.addEventListener('DOMContentLoaded', initCollectionCards);

function initCollectionCards() {
  const grid = document.querySelector('[data-i5="collection__grid"]');
  if (!grid) return;

  const cards = [...grid.querySelectorAll('[data-i5-tags~="collection__card"]')];
  if (!cards.length) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    cards.forEach((card) => card.classList.add('is-col-in'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const card = entry.target;
        const index = cards.indexOf(card);
        window.setTimeout(() => {
          card.classList.add('is-col-in');
        }, Math.max(0, index) * 110);
        observer.unobserve(card);
      });
    },
    { threshold: 0.1, rootMargin: '0px 0px -32px 0px' },
  );

  cards.forEach((card) => observer.observe(card));
}
