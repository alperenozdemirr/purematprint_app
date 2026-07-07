/**
 * loader.js — Açılış splash ekranı
 *
 * NE YAPAR:
 *   data-pmp-site olan sayfalarda kısa yükleme animasyonu gösterir.
 *   Metin döngüsü: baskı/tasarım temalı durum mesajları (Yükleniyor yerine).
 *
 * NE YAPMAZ:
 *   Gerçek asset yükleme takibi — min 400ms / max 1500ms sabit süre.
 *   prefers-reduced-motion açıksa hiç çalışmaz.
 */
(function initSiteLoader() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const MIN_VISIBLE_MS = 400;
  const MAX_WAIT_MS = 1500;
  const MESSAGE_INTERVAL_MS = 450;
  const MESSAGE_FADE_MS = 160;

  /** Baskı stüdyosu temalı durum mesajları — sırayla döner */
  const LOADER_MESSAGES = [
    'Baskı hazırlanıyor…',
    'Tasarım yapılıyor…',
    'Renkler kalibre ediliyor…',
    'Kalıp ayarlanıyor…',
    'Malzeme seçiliyor…',
    'Son kontroller yapılıyor…',
  ];

  let startedAt = 0;
  let finished = false;
  let stopMessages = null;

  function startMessageCycle(labelEl) {
    let index = 0;
    labelEl.textContent = LOADER_MESSAGES[0];

    const intervalId = window.setInterval(() => {
      labelEl.classList.add('is-changing');
      window.setTimeout(() => {
        index = (index + 1) % LOADER_MESSAGES.length;
        labelEl.textContent = LOADER_MESSAGES[index];
        labelEl.classList.remove('is-changing');
      }, MESSAGE_FADE_MS);
    }, MESSAGE_INTERVAL_MS);

    return () => window.clearInterval(intervalId);
  }

  function mount() {
    if (!document.body?.hasAttribute('data-pmp-site')) return;
    if (document.querySelector('[data-pmp-loader]')) return;

    const T = window.TW || {};
    startedAt = Date.now();
    document.documentElement.classList.add('pmp-is-loading');

    const loader = document.createElement('div');
    loader.dataset.pmpLoader = '';
    loader.className = `pmp-loader ${T.loader || ''}`.trim();
    loader.setAttribute('data-i5', 'loader');
    loader.setAttribute('role', 'status');
    loader.setAttribute('aria-live', 'polite');
    loader.setAttribute('aria-busy', 'true');
    loader.innerHTML = `
      <div class="${T.loader__announce || ''}" data-i5="loader__announce">PureMatPrint Stüdyo</div>
      <div class="${T.loader__main || ''}" data-i5="loader__main">
        <div class="${T.loader__logo_box || ''}" data-i5="loader__logo-box">
          <img src="logo.avif" alt="PureMatPrint" width="150" height="48" class="block w-full max-w-[150px] h-auto">
        </div>
        <div class="${T.loader__copy || ''}" data-i5="loader__copy">
          <p class="${T.loader__label || ''}" data-i5="loader__label" data-pmp-loader-label>${LOADER_MESSAGES[0]}</p>
          <div class="${T.loader__track || ''}" data-i5="loader__track" aria-hidden="true">
            <div class="${T.loader__bar || ''}" data-i5="loader__bar"></div>
          </div>
        </div>
      </div>
    `;

    document.body.insertBefore(loader, document.body.firstChild);

    const labelEl = loader.querySelector('[data-pmp-loader-label]');
    if (labelEl) stopMessages = startMessageCycle(labelEl);

    const dismiss = () => {
      if (finished) return;
      finished = true;
      stopMessages?.();
      stopMessages = null;

      const elapsed = Date.now() - startedAt;
      const delay = Math.max(0, MIN_VISIBLE_MS - elapsed);

      window.setTimeout(() => {
        loader.classList.add('is-done');
        loader.setAttribute('aria-busy', 'false');
        document.documentElement.classList.remove('pmp-is-loading');
        window.setTimeout(() => loader.remove(), 500);
      }, delay);
    };

    window.setTimeout(dismiss, MAX_WAIT_MS);
    if (document.readyState === 'complete') {
      dismiss();
    } else {
      document.addEventListener('DOMContentLoaded', dismiss, { once: true });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mount);
  } else {
    mount();
  }
})();
