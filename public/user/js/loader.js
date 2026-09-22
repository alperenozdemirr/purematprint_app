/**
 * loader.js — Açılış splash ekranı
 *
 * Yükleme çubuğu, sayfa kaynaklarının gerçek ilerlemesine göre dolar (akıcı interpolasyon).
 */
(function initSiteLoader() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const SAFETY_MAX_MS = 12000;
  const MESSAGE_INTERVAL_MS = 450;
  const MESSAGE_FADE_MS = 160;
  const PROGRESS_LERP = 0.1;

  const LOADER_MESSAGES = [
    'Baskı hazırlanıyor…',
    'Tasarım yapılıyor…',
    'Renkler kalibre ediliyor…',
    'Kalıp ayarlanıyor…',
    'Malzeme seçiliyor…',
    'Son kontroller yapılıyor…',
  ];

  let finished = false;
  let stopMessages = null;
  let stopProgress = null;
  let pageLoadComplete = false;
  let domReady = false;

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

  function resourceLoadRatio() {
    const resources = performance.getEntriesByType('resource');

    if (resources.length === 0) {
      return domReady ? 0.65 : 0.2;
    }

    let loaded = 0;

    for (const entry of resources) {
      const done = entry.responseEnd > 0
        || (entry.transferSize === 0 && entry.duration >= 0);

      if (done) {
        loaded += 1;
      }
    }

    return loaded / resources.length;
  }

  function computeTargetProgress() {
    if (pageLoadComplete) {
      return 1;
    }

    let target = 0.08;

    if (document.readyState === 'loading') {
      target = Math.max(target, 0.12);
    }

    if (domReady || document.readyState === 'interactive' || document.readyState === 'complete') {
      target = Math.max(target, 0.32);
    }

    const ratio = resourceLoadRatio();
    target = Math.max(target, 0.28 + ratio * 0.62);

    if (document.readyState === 'complete') {
      target = Math.max(target, 0.9);
    }

    return Math.min(target, 0.98);
  }

  function startProgressLoop(barEl, trackEl, onFullyLoaded) {
    let display = 0;
    let rafId = 0;

    const tick = () => {
      const target = computeTargetProgress();
      const delta = target - display;

      if (Math.abs(delta) > 0.0005) {
        display += delta * PROGRESS_LERP;
      } else {
        display = target;
      }

      if (pageLoadComplete && display < 1) {
        display += (1 - display) * 0.14;
      }

      display = Math.max(0, Math.min(display, 1));

      const percent = Math.round(display * 100);
      barEl.style.width = `${percent}%`;
      trackEl.setAttribute('aria-valuenow', String(percent));

      if (pageLoadComplete && display >= 0.995) {
        barEl.style.width = '100%';
        trackEl.setAttribute('aria-valuenow', '100');
        onFullyLoaded();
        return;
      }

      rafId = window.requestAnimationFrame(tick);
    };

    rafId = window.requestAnimationFrame(tick);

    return () => window.cancelAnimationFrame(rafId);
  }

  function mount() {
    if (!document.body?.hasAttribute('data-pmp-site')) return;
    if (document.querySelector('[data-pmp-loader]')) return;

    const T = window.TW || {};
    document.documentElement.classList.add('pmp-is-loading');

    const loader = document.createElement('div');
    loader.dataset.pmpLoader = '';
    loader.className = `pmp-loader ${T.loader || ''}`.trim();
    loader.setAttribute('data-i5', 'loader');
    loader.setAttribute('role', 'status');
    loader.setAttribute('aria-live', 'polite');
    loader.setAttribute('aria-busy', 'true');
    const logoSrc = document.body.dataset.siteLogo || `${window.location.origin}/shared_directory/logo.avif`;
    loader.innerHTML = `
      <div class="${T.loader__announce || ''}" data-i5="loader__announce">PureMatPrint Stüdyo</div>
      <div class="${T.loader__main || ''}" data-i5="loader__main">
        <div class="${T.loader__logo_box || ''}" data-i5="loader__logo-box">
          <img src="${logoSrc}" alt="PureMatPrint" width="150" height="48" class="block w-full max-w-[150px] h-auto">
        </div>
        <div class="${T.loader__copy || ''}" data-i5="loader__copy">
          <p class="${T.loader__label || ''}" data-i5="loader__label" data-pmp-loader-label>${LOADER_MESSAGES[0]}</p>
          <div class="${T.loader__track || ''}" data-i5="loader__track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-label="Sayfa yükleniyor">
            <div class="${T.loader__bar || ''}" data-i5="loader__bar" data-pmp-loader-bar></div>
          </div>
        </div>
      </div>
    `;

    document.body.insertBefore(loader, document.body.firstChild);

    const labelEl = loader.querySelector('[data-pmp-loader-label]');
    const barEl = loader.querySelector('[data-pmp-loader-bar]');
    const trackEl = loader.querySelector('[data-i5="loader__track"]');

    if (labelEl) stopMessages = startMessageCycle(labelEl);

    let dismissScheduled = false;

    const dismiss = () => {
      if (finished || dismissScheduled) return;
      dismissScheduled = true;
      finished = true;
      stopMessages?.();
      stopMessages = null;
      stopProgress?.();
      stopProgress = null;

      loader.classList.add('is-done');
      loader.setAttribute('aria-busy', 'false');
      document.documentElement.classList.remove('pmp-is-loading');
      window.setTimeout(() => loader.remove(), 320);
    };

    const markPageLoaded = () => {
      pageLoadComplete = true;
    };

    if (barEl && trackEl) {
      stopProgress = startProgressLoop(barEl, trackEl, dismiss);
    } else {
      markPageLoaded();
    }

    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      domReady = true;
    } else {
      document.addEventListener('DOMContentLoaded', () => {
        domReady = true;
      }, { once: true });
    }

    if (document.readyState === 'complete') {
      markPageLoaded();
    } else {
      window.addEventListener('load', markPageLoaded, { once: true });
    }

    window.setTimeout(() => {
      markPageLoaded();
      dismiss();
    }, SAFETY_MAX_MS);
  }

  function tryMount() {
    if (document.body) {
      mount();
      return;
    }

    document.addEventListener('DOMContentLoaded', mount, { once: true });
  }

  tryMount();
})();
