/**
 * index5.js — Site geneli etkileşimler
 *
 * NE YAPAR:
 *   Menü, arama, carousel, filtre, login/register formları, WhatsApp butonu.
 *   Tüm sayfalarda yüklenir; ilgili HTML elemanı yoksa fonksiyon sessizce çıkar.
 *
 * NE YAPMAZ:
 *   Sepet, ödeme, ürün detayı, adres listesi — bunlar sayfa özel js/*.js dosyalarında.
 *   Backend/API çağrısı yok; login/register sadece yönlendirme yapar (prototip).
 *
 * HTML bağlantısı: data-i5="..." attribute'larına göre eleman seçilir.
 * State: JS yalnızca is-active, open, is-revealed gibi kısa sınıflar ekler;
 *        stiller HTML'deki Tailwind [&.is-active]:... selector'larında tanımlıdır.
 */

/** WhatsApp sabit butonu — body[data-whatsapp] ile override edilebilir */
const I5_WHATSAPP = {
  phone: '905321234567',
  message: 'Merhaba, PureMatPrint ürünleri hakkında bilgi almak istiyorum.',
};

// Sayfa hazır olunca tüm init fonksiyonları çalışır — sıra önemli değil
document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initMobileShortcuts();
  initSearchOverlay();
  initScrollReveal();
  initTicker();
  initProductCarousels();
  initTestimonialsCarousel();
  initProductGridPagination();
  initProductFilters();
  initProductSort();
  initMegaMenu();
  initAccountDropdown();
  initNavCurrent();
  initFloatActions();
  initPasswordToggle();
  initLoginForm();
  initRegisterForm();
});

// =============================================================================
// GİRİŞ / KAYIT (login.html, register.html)
// Prototip: gerçek API yok, submit sonrası profile.html'e yönlendirir.
// =============================================================================

/** Şifre göster/gizle SVG ikonları */
const PW_TOGGLE_ICON_SHOW =
  '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.036 12.593a1 1 0 010-.186C3.54 7.442 7.674 4.5 12 4.5c4.326 0 8.46 2.942 9.964 6.907a1 1 0 010 .186C20.46 16.558 16.326 19.5 12 19.5c-4.326 0-8.46-2.942-9.964-6.907z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
const PW_TOGGLE_ICON_HIDE =
  '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>';

/** Şifre alanındaki göz ikonuna tıklanınca type password ↔ text değiştirir */
function initPasswordToggle() {
  document.querySelectorAll('[data-i5="login-toggle-pw"]').forEach((btn) => {
    const wrap = btn.closest('[data-i5="login-field__wrap"]');
    const input = wrap?.querySelector('input[type="password"], input[type="text"]');
    if (!input || btn.dataset.pwToggleBound) return;

    btn.dataset.pwToggleBound = '1';

    btn.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Şifreyi gizle' : 'Şifreyi göster');
      btn.setAttribute('aria-pressed', show ? 'true' : 'false');
      btn.innerHTML = show ? PW_TOGGLE_ICON_HIDE : PW_TOGGLE_ICON_SHOW;
    });
  });
}

/** login.html — gerçek doğrulama yok, submit → profile.html */
function initLoginForm() {
  const form = document.getElementById('login-form');
  if (!form || document.body.dataset.page !== 'login') return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    window.location.href = 'profile.html';
  });
}

/** register.html — şifre eşleşme kontrolü, submit → profile.html */
function initRegisterForm() {
  const form = document.getElementById('register-form');
  if (!form || document.body.dataset.page !== 'register') return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const password = form.querySelector('#register-password')?.value || '';
    const confirm = form.querySelector('#register-confirm')?.value || '';
    if (password !== confirm) {
      form.querySelector('#register-confirm')?.setCustomValidity('Şifreler eşleşmiyor');
      form.reportValidity();
      return;
    }

    form.querySelector('#register-confirm')?.setCustomValidity('');
    window.location.href = 'profile.html';
  });

  form.querySelector('#register-confirm')?.addEventListener('input', (event) => {
    event.target.setCustomValidity('');
  });
}

// =============================================================================
// NAVİGASYON — arama overlay, mobil menü, mega menü, aktif link
// =============================================================================

/** Tam ekran arama overlay — #i5-search-open ile açılır, Escape ile kapanır */
function initSearchOverlay() {
  const openBtn = document.getElementById('i5-search-open');
  const overlay = document.getElementById('i5-search');
  const closeBtn = document.getElementById('i5-search-close');
  const input = overlay?.querySelector('input[type="search"]');

  if (!openBtn || !overlay) return;

  const open = () => {
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    input?.focus();
  };

  const shut = () => {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  openBtn.addEventListener('click', open);
  closeBtn?.addEventListener('click', shut);
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) shut();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('open')) shut();
  });
}

/** Hamburger menü — aside#i5-mobile-menu'ye open sınıfı ekler */
function initMobileMenu() {
  const burger = document.getElementById('i5-burger');
  const menu = document.getElementById('i5-mobile-menu');
  const overlay = document.getElementById('i5-mobile-overlay');
  const close = document.getElementById('i5-mobile-close');

  if (!burger || !menu) return;

  const open = () => {
    menu.classList.add('open');
    overlay?.classList.add('open');
    menu.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  };

  const shut = () => {
    menu.classList.remove('open');
    overlay?.classList.remove('open');
    menu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  burger.addEventListener('click', open);
  close?.addEventListener('click', shut);
  overlay?.addEventListener('click', shut);
  menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', shut));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && menu.classList.contains('open')) shut();
  });
}

/** Mobil menüde Sepet/Hesabım kısayollarına is-active verir (mevcut sayfaya göre) */
function initMobileShortcuts() {
  const menu = document.getElementById('i5-mobile-menu');
  if (!menu) return;

  const file = window.location.pathname.split('/').pop() || 'index.html';
  const accountPages = new Set(['profile.html', 'orders.html', 'addresses.html', 'login.html', 'register.html', 'address-new.html', 'order-detail.html', 'order-cancel.html']);

  menu.querySelectorAll('a[href="cart.html"], a[href="profile.html"]').forEach((link) => {
    if (!link.closest('.grid')) return;
    const href = link.getAttribute('href');
    const active =
      (file === 'cart.html' && href === 'cart.html') ||
      (accountPages.has(file) && href === 'profile.html');
    link.classList.toggle('is-active', active);
  });
}

// =============================================================================
// ANASAYFA — scroll reveal, ticker, carousel, yorumlar slider
// İlgili eleman yoksa fonksiyon sessizce çıkar (diğer sayfalarda sorun olmaz).
// =============================================================================

/** Viewport'a girince is-revealed ekler — animasyon CSS'te tanımlı */
function initScrollReveal() {
  const els = document.querySelectorAll(
    '.reveal, .reveal-left, .reveal-right, .reveal-scale, [data-i5="reveal"], [data-i5-tags~="reveal"]',
  );
  if (!els.length) return;

  const reveal = (el) => {
    el.classList.add('is-revealed');
    observer.unobserve(el);
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) reveal(entry.target);
      });
    },
    { threshold: 0.08, rootMargin: '0px 0px -20px 0px' },
  );

  els.forEach((el) => {
    observer.observe(el);
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight && rect.bottom > 0) {
      reveal(el);
    }
  });
}

/** Anasayfa kayan yazı bandı — içeriği klonlayıp sonsuz CSS animasyonu oluşturur */
function initTicker() {
  const track = document.querySelector('[data-i5-ticker]');
  if (!track || track.dataset.i5TickerInit === 'true') return;
  track.dataset.i5TickerInit = 'true';

  const wrap = track.parentElement;
  const group = document.createElement('div');
  group.className = 'flex shrink-0 items-center gap-12 pr-12';
  while (track.firstChild) {
    group.appendChild(track.firstChild);
  }
  track.appendChild(group);

  const pxPerSec = 80;

  /** Ekran hiç boş kalmaması için: toplam genişlik >= viewport + bir grup */
  const fillTrack = () => {
    const groupWidth = group.offsetWidth;
    const viewport = wrap?.offsetWidth || window.innerWidth;
    if (!groupWidth || !viewport) return false;

    while (track.children.length > 1) {
      track.lastElementChild?.remove();
    }

    const minWidth = viewport + groupWidth;
    let guard = 0;
    while ((track.children.length < 2 || track.scrollWidth < minWidth) && guard < 24) {
      const clone = group.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
      guard += 1;
    }
    return true;
  };

  const syncTicker = () => {
    if (!fillTrack()) return false;
    const width = group.offsetWidth;
    track.style.setProperty('--ticker-shift', `-${width}px`);
    track.style.setProperty('--ticker-duration', `${width / pxPerSec}s`);
    return true;
  };

  const startTicker = () => {
    if (!syncTicker()) {
      requestAnimationFrame(startTicker);
      return;
    }
    track.classList.add('animate-i5-ticker');
    track.dataset.i5TickerReady = 'true';
  };

  requestAnimationFrame(startTicker);

  if (document.fonts?.ready) {
    document.fonts.ready.then(() => {
      syncTicker();
      if (!track.classList.contains('animate-i5-ticker')) {
        requestAnimationFrame(startTicker);
      }
    });
  }

  const onResize = () => {
    if (!group.offsetWidth) return;
    const wasRunning = track.classList.contains('animate-i5-ticker');
    if (wasRunning) track.classList.remove('animate-i5-ticker');
    syncTicker();
    if (wasRunning) {
      void track.offsetWidth;
      track.classList.add('animate-i5-ticker');
    }
  };

  if ('ResizeObserver' in window) {
    let resizeRaf = 0;
    const observer = new ResizeObserver(() => {
      if (resizeRaf) cancelAnimationFrame(resizeRaf);
      resizeRaf = requestAnimationFrame(() => {
        resizeRaf = 0;
        onResize();
      });
    });
    observer.observe(group);
    if (wrap) observer.observe(wrap);
  } else {
    window.addEventListener('resize', onResize);
  }
}

/** Ürün carousel ok butonları — yatay scrollBy ile kaydırır */
function initProductCarousels() {
  document.querySelectorAll('[data-i5-product-carousel]').forEach((carousel) => {
    const section = carousel.closest('section');
    const prev = section?.querySelector('[data-i5-carousel-prev]');
    const next = section?.querySelector('[data-i5-carousel-next]');

    const getScroller = () => {
      const wrap = carousel.parentElement;
      if (
        wrap &&
        (matchesI5('carousel-wrap', wrap) ||
          matchesI5('band__track-wrap', wrap)) &&
        wrap.scrollWidth > wrap.clientWidth
      ) {
        return wrap;
      }
      return carousel;
    };

    const scroll = (dir) => {
      const scroller = getScroller();
      const item =
        carousel.querySelector('[data-i5="carousel__item"], [data-i5-tags*="carousel__item"]') ||
        carousel.querySelector('article');
      if (!item) return;
      const gap = parseFloat(getComputedStyle(carousel).gap) || 0;
      scroller.scrollBy({ left: dir * (item.offsetWidth + gap), behavior: 'smooth' });
    };

    prev?.addEventListener('click', () => scroll(-1));
    next?.addEventListener('click', () => scroll(1));
  });
}

/** Müşteri yorumları slider — 5 sn otomatik geçiş, ok ile manuel */
function initTestimonialsCarousel() {
  document.querySelectorAll('[data-i5-testimonials]').forEach((slider) => {
    const section = slider.closest('section');
    const slides = [...slider.querySelectorAll('[data-i5="testimonials__slide"]')];
    const prev = section?.querySelector('[data-i5-testimonials-prev]');
    const next = section?.querySelector('[data-i5-testimonials-next]');
    if (slides.length < 2) return;

    let index = slides.findIndex((slide) => slide.classList.contains('is-active'));
    if (index < 0) index = 0;

    let timer;

    const show = (nextIndex) => {
      slides[index]?.classList.remove('is-active');
      index = (nextIndex + slides.length) % slides.length;
      slides[index]?.classList.add('is-active');
    };

    const restart = () => {
      clearInterval(timer);
      timer = setInterval(() => show(index + 1), 5000);
    };

    prev?.addEventListener('click', () => {
      show(index - 1);
      restart();
    });

    next?.addEventListener('click', () => {
      show(index + 1);
      restart();
    });

    slider.addEventListener('mouseenter', () => clearInterval(timer));
    slider.addEventListener('mouseleave', restart);

    restart();
  });
}

// =============================================================================
// KATALOG — ürün grid sayfalama ve filtre butonları
// =============================================================================

function queryProductCards(root) {
  return [...root.querySelectorAll('[data-i5="product"], [data-i5-tags~="product"]')];
}

/** products.html vb. — grid'deki [data-i5-product-grid] elemanlarına sayfalama bağlar */
function initProductGridPagination() {
  document.querySelectorAll('[data-i5-product-grid]').forEach((grid) => {
    I5ProductPagination.bind(grid);
  });
}

const I5ProductPagination = {
  controllers: new WeakMap(),

  bind(grid) {
    if (!grid) return;
    const nav = grid.parentElement?.querySelector('[data-i5-pagination]');
    if (!nav) return;

    const existing = this.controllers.get(grid);
    if (existing?.resizeHandler) {
      window.removeEventListener('resize', existing.resizeHandler);
    }

    const controller = createProductGridPaginationController(grid, nav);
    this.controllers.set(grid, controller);
    controller.renderPage(1, false);
  },

  refresh(grid) {
    const controller = this.controllers.get(grid);
    if (controller) controller.renderPage(1, false);
  },
};

/** Sayfa başına ürün sayısı — ekran genişliğine göre hesaplanır */
function createProductGridPaginationController(grid, nav) {
  let currentPage = 1;
  let totalPages = 1;

  const getVisibleItems = () =>
    queryProductCards(grid).filter((item) => !item.classList.contains('is-filter-hidden'));

  const getPerPage = () => {
    const rows = Number(grid.dataset.i5Rows) || 3;
    const configured = Number(grid.dataset.i5PerPage);
    if (configured > 0) return configured;
    if (window.innerWidth >= 1024) return 4 * rows;
    if (window.innerWidth >= 768) return 3 * rows;
    return 2 * rows;
  };

  const renderPage = (page, shouldScroll) => {
    const items = getVisibleItems();
    const perPage = getPerPage();
    totalPages = Math.max(1, Math.ceil(items.length / perPage));
    currentPage = Math.min(Math.max(1, page), totalPages);

    // is-filter-hidden: filtre dışı ürünler sayfalamaya dahil edilmez
    queryProductCards(grid).forEach((item) => {
      if (item.classList.contains('is-filter-hidden')) {
        item.classList.add('is-page-hidden');
        return;
      }

      const index = items.indexOf(item);
      const inPage = index >= (currentPage - 1) * perPage && index < currentPage * perPage;
      item.classList.toggle('is-page-hidden', !inPage);
      if (inPage) item.classList.add('is-revealed');
    });

    nav.innerHTML = '';
    if (totalPages <= 1 || items.length === 0) {
      nav.hidden = true;
      return;
    }

    nav.hidden = false;

    const prev = document.createElement('button');
    prev.type = 'button';
    prev.className = window.TW?.pagination__btn || '';
    prev.textContent = '←';
    prev.setAttribute('aria-label', 'Önceki sayfa');
    prev.disabled = currentPage === 1;
    prev.addEventListener('click', () => renderPage(currentPage - 1, true));
    nav.appendChild(prev);

    buildPageList(currentPage, totalPages).forEach((entry) => {
      if (entry === '…') {
        const dots = document.createElement('span');
        dots.className = window.TW?.pagination__dots || '';
        dots.textContent = '…';
        dots.setAttribute('aria-hidden', 'true');
        nav.appendChild(dots);
        return;
      }

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = window.TW?.pagination__btn || '';
      if (entry === currentPage) btn.classList.add('is-active');
      btn.textContent = String(entry);
      btn.setAttribute('aria-label', `Sayfa ${entry}`);
      if (entry === currentPage) btn.setAttribute('aria-current', 'page');
      btn.addEventListener('click', () => renderPage(entry, true));
      nav.appendChild(btn);
    });

    const next = document.createElement('button');
    next.type = 'button';
    next.className = window.TW?.pagination__btn || '';
    next.textContent = '→';
    next.setAttribute('aria-label', 'Sonraki sayfa');
    next.disabled = currentPage === totalPages;
    next.addEventListener('click', () => renderPage(currentPage + 1, true));
    nav.appendChild(next);

    if (shouldScroll) {
      grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  let resizeTimer;
  const resizeHandler = () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => renderPage(currentPage, false), 150);
  };

  window.addEventListener('resize', resizeHandler);

  return { renderPage, resizeHandler };
}

function buildPageList(current, total) {
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1);
  }

  const pages = [1];
  const start = Math.max(2, current - 1);
  const end = Math.min(total - 1, current + 1);

  if (start > 2) pages.push('…');
  for (let i = start; i <= end; i += 1) pages.push(i);
  if (end < total - 1) pages.push('…');
  pages.push(total);

  return pages;
}

/** products.html — kategori filtre butonları; URL ?cat= parametresini günceller */
function initProductFilters() {
  const filters = document.getElementById('product-filters');
  const grid = document.getElementById('product-grid');
  if (!filters || !grid) return;

  const aliases = { kartvizit: 'kurumsal' };

  // HTML'deki <a> filtre linklerini <button> yap — sayfa yenilenmeden filtreleme için
  filters.querySelectorAll('a[data-i5="filter"]').forEach((link) => {
    const href = link.getAttribute('href') || '';
    const match = href.match(/[?&]cat=([^&]+)/);
    const raw = match ? match[1] : 'all';
    const filterId = raw === 'all' ? 'all' : (aliases[raw] || raw);
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = link.className;
    btn.dataset.filter = filterId;
    btn.textContent = link.textContent.trim();
    btn.setAttribute('role', 'tab');
    link.replaceWith(btn);
  });

  filters.querySelectorAll('[data-filter="kartvizit"]').forEach((el) => el.remove());

  filters.querySelectorAll('button[data-i5="filter"][data-filter]').forEach((btn) => {
    btn.setAttribute('role', 'tab');
  });

  const countEl = document.getElementById('product-count');

  const setActiveFilter = (category) => {
    filters.querySelectorAll('[data-filter]').forEach((btn) => {
      const isActive = btn.dataset.filter === category;
      btn.classList.toggle('is-active', isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
  };

  const syncFilterUrl = (category) => {
    const url = new URL(window.location.href);
    if (category === 'all') url.searchParams.delete('cat');
    else url.searchParams.set('cat', category);
    window.history.replaceState({ filter: category }, '', url);
  };

  const applyFilter = (category) => {
    setActiveFilter(category);

    let visible = 0;
    queryProductCards(grid).forEach((product) => {
      const match = category === 'all' || product.dataset.category === category;
      product.classList.toggle('is-filter-hidden', !match);
      if (match) visible += 1;
    });

    if (countEl) countEl.textContent = `${visible} ürün`;
    if (typeof I5ProductPagination !== 'undefined') I5ProductPagination.refresh(grid);
    syncFilterUrl(category);
  };

  filters.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-filter]');
    if (!trigger || !filters.contains(trigger)) return;
    event.preventDefault();
    applyFilter(trigger.dataset.filter);
  });

  window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);
    const rawCat = params.get('cat');
    const category = rawCat ? (aliases[rawCat] || rawCat) : 'all';
    const isValid = category === 'all' || filters.querySelector(`[data-filter="${category}"]`);
    applyFilter(isValid ? category : 'all');
  });

  const params = new URLSearchParams(window.location.search);
  const rawCat = params.get('cat');
  const initial = rawCat ? (aliases[rawCat] || rawCat) : 'all';
  const isValid = initial === 'all' || filters.querySelector(`[data-filter="${initial}"]`);
  applyFilter(isValid ? initial : 'all');
}

/** products.html / category.html — fiyat ve isim sıralaması */
function initProductSort() {
  const grid = document.getElementById('product-grid');
  const sortEl = document.getElementById('product-sort');
  if (!grid || !sortEl || sortEl.dataset.i5SortInit === 'true') return;
  sortEl.dataset.i5SortInit = 'true';

  const sortProducts = (mode) => {
    const items = queryProductCards(grid);

    items.sort((a, b) => {
      if (mode === 'price-asc') {
        return Number(a.dataset.price || 0) - Number(b.dataset.price || 0);
      }
      if (mode === 'price-desc') {
        return Number(b.dataset.price || 0) - Number(a.dataset.price || 0);
      }
      if (mode === 'name') {
        const nameA = a.querySelector('[data-i5="product__name"]')?.textContent.trim() || '';
        const nameB = b.querySelector('[data-i5="product__name"]')?.textContent.trim() || '';
        return nameA.localeCompare(nameB, 'tr');
      }
      return 0;
    });

    items.forEach((item) => grid.appendChild(item));

    if (typeof I5ProductPagination !== 'undefined') {
      I5ProductPagination.refresh(grid);
    }
  };

  sortEl.addEventListener('change', (e) => sortProducts(e.target.value));
  initSortDropdown(sortEl);
}

/** Native select yerine özel sıralama menüsü — mobil ve masaüstü */
function initSortDropdown(sortEl) {
  const wrap = sortEl.closest('[data-i5="shop__sort"]');
  if (!wrap || wrap.dataset.i5SortDropdown === 'ready') return;
  wrap.dataset.i5SortDropdown = 'ready';

  let outsideHandler = null;
  let escapeHandler = null;

  const getLabel = (value) => {
    const opt = sortEl.querySelector(`option[value="${CSS.escape(value)}"]`);
    return opt ? opt.textContent.trim() : '';
  };

  sortEl.classList.add('!absolute', '!w-px', '!h-px', '!opacity-0', '!overflow-hidden', '!pointer-events-none');
  sortEl.tabIndex = -1;

  const trigger = document.createElement('button');
  trigger.type = 'button';
  trigger.className =
    'w-full min-w-0 min-[900px]:w-auto px-3.5 py-2.5 pr-9 border-[3px] border-ink bg-surface font-body text-[13px] max-[899px]:text-xs font-semibold leading-snug text-ink shadow-brutal-sm cursor-pointer outline-none focus:shadow-brutal text-left whitespace-nowrap';
  trigger.textContent = getLabel(sortEl.value);
  trigger.setAttribute('aria-haspopup', 'listbox');
  trigger.setAttribute('aria-expanded', 'false');
  trigger.setAttribute('aria-label', sortEl.getAttribute('aria-label') || 'Sıralama');

  const menu = document.createElement('ul');
  menu.className =
    'absolute left-0 right-0 min-[900px]:right-auto min-[900px]:min-w-full top-[calc(100%+4px)] z-[60] m-0 list-none border-[3px] border-ink bg-surface p-0 shadow-brutal whitespace-nowrap';
  menu.setAttribute('role', 'listbox');
  menu.hidden = true;

  const closeMenu = () => {
    menu.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    wrap.classList.remove('is-open');
    if (outsideHandler) {
      document.removeEventListener('click', outsideHandler);
      outsideHandler = null;
    }
    if (escapeHandler) {
      document.removeEventListener('keydown', escapeHandler);
      escapeHandler = null;
    }
  };

  const openMenu = () => {
    menu.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
    wrap.classList.add('is-open');
    outsideHandler = (e) => {
      if (!wrap.contains(e.target)) closeMenu();
    };
    setTimeout(() => document.addEventListener('click', outsideHandler), 0);
    escapeHandler = (e) => {
      if (e.key === 'Escape') closeMenu();
    };
    document.addEventListener('keydown', escapeHandler);
  };

  const selectOption = (value) => {
    sortEl.value = value;
    sortEl.dispatchEvent(new Event('change', { bubbles: true }));
    trigger.textContent = getLabel(value);
    menu.querySelectorAll('[role="option"]').forEach((btn) => {
      btn.setAttribute('aria-selected', btn.dataset.value === value ? 'true' : 'false');
    });
    closeMenu();
  };

  [...sortEl.options].forEach((opt) => {
    const li = document.createElement('li');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.dataset.value = opt.value;
    btn.className =
      'block w-full px-3.5 py-2.5 text-left font-body text-[13px] max-[899px]:text-xs font-semibold text-ink bg-surface border-0 border-b border-ink/10 last:border-b-0 cursor-pointer hover:bg-hover focus-visible:outline-2 focus-visible:outline-action';
    btn.textContent = opt.textContent.trim();
    btn.setAttribute('role', 'option');
    btn.setAttribute('aria-selected', opt.value === sortEl.value ? 'true' : 'false');
    btn.addEventListener('click', () => selectOption(opt.value));
    li.appendChild(btn);
    menu.appendChild(li);
  });

  trigger.addEventListener('click', (e) => {
    e.stopPropagation();
    if (menu.hidden) openMenu();
    else closeMenu();
  });

  wrap.appendChild(trigger);
  wrap.appendChild(menu);
}

/** Masaüstü mega menü — hover ile is-open; mobilde CSS :hover zaten devre dışı */
function initMegaMenu() {
  const mq = window.matchMedia('(min-width: 1040px)');
  const items = [...document.querySelectorAll('[data-i5="mega-item"]')];
  if (!items.length) return;

  let closeTimer;

  const closeAll = () => {
    items.forEach((item) => item.classList.remove('is-open'));
  };

  const openItem = (item) => {
    clearTimeout(closeTimer);
    closeAll();
    item.classList.add('is-open');
  };

  const scheduleClose = () => {
    clearTimeout(closeTimer);
    closeTimer = setTimeout(closeAll, 150);
  };

  const bind = () => {
    items.forEach((item) => {
      const panel = item.querySelector('[data-i5="mega-panel"]');
      if (!panel) return;

      item.onmouseenter = () => {
        if (mq.matches) openItem(item);
      };
      item.onmouseleave = () => {
        if (mq.matches) scheduleClose();
      };
      panel.onmouseenter = () => {
        if (mq.matches) openItem(item);
      };
      panel.onmouseleave = () => {
        if (mq.matches) scheduleClose();
      };
    });
  };

  bind();
  mq.addEventListener('change', () => {
    closeAll();
    bind();
  });
}

/** Masaüstü hesap menüsü — hover / tıklama ile açılır panel */
function initAccountDropdown() {
  const mq = window.matchMedia('(min-width: 1040px)');
  const dropdown = document.querySelector('[data-i5="account-dropdown"]');
  if (!dropdown) return;

  const trigger = dropdown.querySelector('[data-i5="account-trigger"]');
  if (!trigger) return;

  let closeTimer;

  const setExpanded = (open) => {
    trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    dropdown.classList.toggle('is-open', open);
  };

  const open = () => {
    clearTimeout(closeTimer);
    setExpanded(true);
  };

  const scheduleClose = () => {
    clearTimeout(closeTimer);
    closeTimer = setTimeout(() => setExpanded(false), 150);
  };

  const bind = () => {
    dropdown.onmouseenter = () => {
      if (mq.matches) open();
    };
    dropdown.onmouseleave = () => {
      if (mq.matches) scheduleClose();
    };

    trigger.onclick = (event) => {
      if (!mq.matches) return;
      event.preventDefault();
      setExpanded(!dropdown.classList.contains('is-open'));
    };
  };

  bind();

  mq.addEventListener('change', () => {
    setExpanded(false);
    bind();
  });

  document.addEventListener('click', (event) => {
    if (mq.matches && !dropdown.contains(event.target)) {
      setExpanded(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setExpanded(false);
  });
}

/** href → "products.html" veya "category.html?cat=x" normalize eder */
function normalizeNavKey(href) {
  if (!href || href.startsWith('#')) return null;
  const url = new URL(href, window.location.href);
  const file = url.pathname.split('/').pop() || 'index.html';
  const cat = url.searchParams.get('cat');
  return cat ? `${file}?cat=${cat}` : file;
}

function getCurrentNavKey() {
  const file = window.location.pathname.split('/').pop() || 'index.html';
  const cat = new URLSearchParams(window.location.search).get('cat');

  if (file === 'category.html' && cat) {
    return `category.html?cat=${cat}`;
  }

  if (file === 'product.html') {
    const categoryLink = document.querySelector('[data-i5="pdp-info__category"][href]');
    if (categoryLink) return normalizeNavKey(categoryLink.getAttribute('href'));
  }

  const staticPages = [
    'products.html',
    'bestsellers.html',
    'categories.html',
    'collection.html',
    'about.html',
    'contact.html',
  ];

  if (staticPages.includes(file)) return file;
  return null;
}

/** Mevcut sayfaya göre nav linklerine is-current + aria-current ekler */
function initNavCurrent() {
  const current = getCurrentNavKey();
  if (!current) return;

  document.querySelectorAll('[data-i5="mega-nav__link"], [data-i5="mobile__link"]').forEach((link) => {
    link.classList.remove('is-current');
    link.removeAttribute('aria-current');

    if (normalizeNavKey(link.getAttribute('href')) === current) {
      link.classList.add('is-current');
      link.setAttribute('aria-current', 'page');
    }
  });
}

// =============================================================================
// SABİT BUTONLAR — sağ alttaki WhatsApp
// =============================================================================

/** Sağ alttaki WhatsApp + yukarı çık butonları — JS ile DOM'a eklenir (HTML'de yok) */
function initFloatActions() {
  if (!document.body?.hasAttribute('data-pmp-site')) return;

  const T = window.TW || {};
  const phone = document.body.dataset.whatsapp || I5_WHATSAPP.phone;
  const message = document.body.dataset.whatsappMessage || I5_WHATSAPP.message;
  const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

  const stack = document.createElement('div');
  stack.className = T.float_stack || '';
  stack.dataset.i5 = 'float-stack';

  const whatsapp = document.createElement('a');
  whatsapp.className = `${T.float_btn || ''} ${T.whatsapp || ''}`.trim();
  whatsapp.href = waUrl;
  whatsapp.target = '_blank';
  whatsapp.rel = 'noopener noreferrer';
  whatsapp.setAttribute('aria-label', 'WhatsApp ile iletişime geç');
  whatsapp.innerHTML =
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.884 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';

  const backTop = document.createElement('button');
  backTop.type = 'button';
  backTop.className = `${T.float_btn || ''} ${T.back_top || ''}`.trim();
  backTop.setAttribute('aria-label', 'Yukarı çık');
  backTop.innerHTML =
    '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>';

  stack.append(whatsapp, backTop);
  document.body.appendChild(stack);

  const toggleBackTop = () => {
    backTop.classList.toggle('is-visible', window.scrollY > 400);
  };

  backTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  window.addEventListener('scroll', toggleBackTop, { passive: true });
  toggleBackTop();
}
