/**
 * product.js — Ürün detay sayfası (product.html)
 *
 * NE YAPAR:
 *   Galeri thumb/slide geçişi, lightbox, boyut/varyant seçimi (is-active).
 *
 * NE YAPMAZ:
 *   Sepete ekleme, stok kontrolü, fiyat güncelleme — HTML statik prototip.
 */

document.addEventListener('DOMContentLoaded', () => {
  initPdpGallery();
  initPdpLightbox();
  initPdpOptions();
});

/** Yatay kaydırmalı galeri — thumb tıklama ve ok tuşları ile slide değiştirir */
function initPdpGallery() {
  document.querySelectorAll('[data-i5-pdp-gallery]').forEach((gallery) => {
    const track = gallery.querySelector('[data-i5-pdp-track]');
    const slides = [...gallery.querySelectorAll('[data-i5="pdp-gallery__slide"]')];
    const thumbs = [...gallery.querySelectorAll('[data-i5="pdp-gallery__thumb"]')];
    const prev = gallery.querySelector('[data-i5-pdp-prev]');
    const next = gallery.querySelector('[data-i5-pdp-next]');

    if (!track || slides.length === 0) return;

    let index = 0;
    let scrollTimer;

    const syncThumbs = () => {
      thumbs.forEach((thumb, i) => {
        const active = i === index;
        thumb.classList.toggle('is-active', active);
        if (active) {
          thumb.setAttribute('aria-current', 'true');
        } else {
          thumb.removeAttribute('aria-current');
        }
      });
    };

    const goTo = (nextIndex, behavior = 'smooth') => {
      index = (nextIndex + slides.length) % slides.length;
      track.scrollTo({
        left: slides[index].offsetLeft,
        behavior,
      });
      syncThumbs();
    };

    const updateIndexFromScroll = () => {
      const scrollLeft = track.scrollLeft;
      let closest = 0;
      let minDist = Infinity;

      slides.forEach((slide, i) => {
        const dist = Math.abs(slide.offsetLeft - scrollLeft);
        if (dist < minDist) {
          minDist = dist;
          closest = i;
        }
      });

      if (closest !== index) {
        index = closest;
        syncThumbs();
      }
    };

    track.addEventListener('scroll', () => {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(updateIndexFromScroll, 60);
    }, { passive: true });

    if ('onscrollend' in track) {
      track.addEventListener('scrollend', updateIndexFromScroll, { passive: true });
    }

    prev?.addEventListener('click', () => goTo(index - 1));
    next?.addEventListener('click', () => goTo(index + 1));

    thumbs.forEach((thumb, i) => {
      thumb.addEventListener('click', () => goTo(i));
    });

    track.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        goTo(index - 1);
      }
      if (event.key === 'ArrowRight') {
        event.preventDefault();
        goTo(index + 1);
      }
    });

    window.addEventListener('resize', () => {
      goTo(index, 'auto');
    });

    syncThumbs();
  });
}

/** Görsele tıklanınca tam ekran lightbox açar — DOM'a dinamik eklenir */
function initPdpLightbox() {
  document.querySelectorAll('[data-i5-pdp-gallery]').forEach((gallery) => {
    const slides = [...gallery.querySelectorAll('[data-i5="pdp-gallery__slide"] img')];
    if (!slides.length) return;

    const images = slides.map((img) => ({
      src: img.currentSrc || img.src,
      alt: img.alt,
    }));

    let index = 0;
    let lightbox = null;
    let lastFocus = null;

    const ensureLightbox = () => {
      if (lightbox) return lightbox;

      const T = window.TW || {};
      lightbox = document.createElement('div');
      lightbox.className = T.pdp_lightbox || '';
      lightbox.dataset.i5 = 'pdp-lightbox';
      lightbox.hidden = true;
      lightbox.setAttribute('role', 'dialog');
      lightbox.setAttribute('aria-modal', 'true');
      lightbox.setAttribute('aria-label', 'Ürün görseli');
      lightbox.innerHTML = `
        <button type="button" class="${T.pdp_lightbox__backdrop || ''}" data-i5="pdp-lightbox__backdrop" data-i5-pdp-lightbox-close aria-label="Kapat"></button>
        <div class="${T.pdp_lightbox__panel || ''}" data-i5="pdp-lightbox__panel">
          <button type="button" class="${T.pdp_lightbox__close || ''}" data-i5="pdp-lightbox__close" data-i5-pdp-lightbox-close aria-label="Kapat">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
          </button>
          <button type="button" class="${T.pdp_lightbox__nav || ''} ${T.pdp_lightbox__nav__prev || ''}" data-i5="pdp-lightbox__nav" data-i5-pdp-lightbox-prev aria-label="Önceki görsel">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <button type="button" class="${T.pdp_lightbox__nav || ''} ${T.pdp_lightbox__nav__next || ''}" data-i5="pdp-lightbox__nav" data-i5-pdp-lightbox-next aria-label="Sonraki görsel">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
          </button>
          <figure>
            <img class="${T.pdp_lightbox__img || ''}" data-i5="pdp-lightbox__img" src="" alt="">
            <figcaption class="${T.pdp_lightbox__caption || ''}" data-i5="pdp-lightbox__caption"></figcaption>
          </figure>
        </div>
      `;

      document.body.appendChild(lightbox);

      const imgEl = lightbox.querySelector('[data-i5="pdp-lightbox__img"]');
      const captionEl = lightbox.querySelector('[data-i5="pdp-lightbox__caption"]');
      const prevBtn = lightbox.querySelector('[data-i5-pdp-lightbox-prev]');
      const nextBtn = lightbox.querySelector('[data-i5-pdp-lightbox-next]');

      const render = () => {
        const item = images[index];
        imgEl.src = item.src;
        imgEl.alt = item.alt;
        captionEl.textContent = `${index + 1} / ${images.length}`;
        prevBtn.disabled = images.length <= 1;
        nextBtn.disabled = images.length <= 1;
      };

      const close = () => {
        lightbox.hidden = true;
        lightbox.setAttribute('hidden', '');
        lightbox.classList.add('hidden');
        document.documentElement.classList.remove('pmp-lightbox-open');
        document.removeEventListener('keydown', onKeydown);
        lastFocus?.focus();
      };

      const open = (startIndex) => {
        index = startIndex;
        render();
        lastFocus = document.activeElement;
        lightbox.hidden = false;
        lightbox.removeAttribute('hidden');
        lightbox.classList.remove('hidden');
        document.documentElement.classList.add('pmp-lightbox-open');
        lightbox.querySelector('[data-i5="pdp-lightbox__close"]')?.focus();
        document.addEventListener('keydown', onKeydown);
      };

      const step = (delta) => {
        index = (index + delta + images.length) % images.length;
        render();
      };

      const onKeydown = (event) => {
        if (lightbox.hidden) return;
        if (event.key === 'Escape') {
          event.preventDefault();
          close();
        }
        if (event.key === 'ArrowLeft') {
          event.preventDefault();
          step(-1);
        }
        if (event.key === 'ArrowRight') {
          event.preventDefault();
          step(1);
        }
      };

      lightbox.querySelectorAll('[data-i5-pdp-lightbox-close]').forEach((btn) => {
        btn.addEventListener('click', (event) => {
          event.preventDefault();
          event.stopPropagation();
          close();
        });
      });

      prevBtn.addEventListener('click', () => step(-1));
      nextBtn.addEventListener('click', () => step(1));

      lightbox.openAt = open;
      return lightbox;
    };

    slides.forEach((img, slideIndex) => {
      let pointerStart = null;

      img.addEventListener('pointerdown', (event) => {
        pointerStart = { x: event.clientX, y: event.clientY };
      });

      img.addEventListener('click', (event) => {
        if (pointerStart) {
          const dx = Math.abs(event.clientX - pointerStart.x);
          const dy = Math.abs(event.clientY - pointerStart.y);
          if (dx > 10 || dy > 10) return;
        }
        event.preventDefault();
        ensureLightbox().openAt(slideIndex);
      });
    });
  });
}

/** Boyut/renk butonları — tek seçim, is-active + aria-pressed güncellenir */
function initPdpOptions() {
  document.querySelectorAll('[data-i5="pdp-option__values"]').forEach((group) => {
    const buttons = [...group.querySelectorAll('[data-i5="pdp-option__btn"]')];
    if (!buttons.length) return;

    const setActive = (activeBtn) => {
      buttons.forEach((btn) => {
        const isActive = btn === activeBtn;
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });
    };

    buttons.forEach((btn) => {
      btn.setAttribute('aria-pressed', btn.classList.contains('is-active') ? 'true' : 'false');
      btn.addEventListener('click', () => setActive(btn));
    });
  });
}
