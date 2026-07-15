/**
 * search.js — Header arama overlay canlı önerileri
 */

document.addEventListener('DOMContentLoaded', () => {
  const overlay = document.getElementById('i5-search');
  const input = overlay?.querySelector('[data-search-input]');
  const resultsEl = overlay?.querySelector('[data-search-results]');
  const searchUrl = overlay?.dataset.searchUrl;
  const shopsUrl = overlay?.dataset.shopsUrl;

  if (!overlay || !input || !resultsEl || !searchUrl || !shopsUrl) return;

  let timer = null;
  let controller = null;

  const hideResults = () => {
    resultsEl.classList.add('hidden');
    resultsEl.innerHTML = '';
  };

  const renderResults = (payload, term) => {
    const results = payload.results || [];

    if (!term || term.length < 2) {
      hideResults();
      return;
    }

    if (!results.length) {
      resultsEl.innerHTML = `
        <div class="px-5 py-8 text-center">
          <p class="font-body text-[15px] font-bold text-ink">Sonuç bulunamadı</p>
          <p class="mt-1 font-body text-[13px] text-muted">“${escapeHtml(term)}” için eşleşen ürün yok.</p>
        </div>`;
      resultsEl.classList.remove('hidden');
      return;
    }

    const items = results.map((item) => `
      <a href="${item.url}" class="flex items-center gap-4 border-b border-ink/10 px-4 py-3 transition-colors last:border-b-0 hover:bg-hover">
        <div class="h-14 w-14 shrink-0 overflow-hidden border-[3px] border-ink bg-bg">
          <img src="${item.image}" alt="" class="h-full w-full object-cover">
        </div>
        <div class="min-w-0 flex-1">
          <p class="truncate font-body text-[14px] font-bold text-ink">${escapeHtml(item.title)}</p>
          <p class="font-body text-[12px] text-muted">${escapeHtml(item.code)}</p>
        </div>
        <span class="shrink-0 font-body text-[13px] font-bold text-ink">${escapeHtml(item.price)}</span>
      </a>
    `).join('');

    resultsEl.innerHTML = `
      <div class="border-b-[3px] border-ink bg-bg px-4 py-3">
        <p class="font-body text-[11px] font-bold uppercase tracking-[0.08em] text-muted">Hızlı Sonuçlar</p>
      </div>
      ${items}
      <a href="${payload.total_url}" class="block border-t-[3px] border-ink bg-action px-4 py-3.5 text-center font-body text-[12px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-action-hover">
        Tüm sonuçları gör →
      </a>`;
    resultsEl.classList.remove('hidden');
  };

  const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const fetchResults = (term) => {
    if (controller) controller.abort();
    controller = new AbortController();

    fetch(`${searchUrl}?q=${encodeURIComponent(term)}`, {
      headers: { Accept: 'application/json' },
      signal: controller.signal,
    })
      .then((response) => response.json())
      .then((payload) => renderResults(payload, term))
      .catch((error) => {
        if (error.name !== 'AbortError') hideResults();
      });
  };

  input.addEventListener('input', () => {
    const term = input.value.trim();
    window.clearTimeout(timer);

    if (term.length < 2) {
      hideResults();
      return;
    }

    timer = window.setTimeout(() => fetchResults(term), 220);
  });

  overlay.addEventListener('click', (event) => {
    if (event.target === overlay) hideResults();
  });

  document.getElementById('i5-search-close')?.addEventListener('click', hideResults);
});
