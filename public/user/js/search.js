/**
 * search.js — Header / mobil menü canlı arama önerileri
 */

document.addEventListener('DOMContentLoaded', () => {
  const defaultRoot = document.getElementById('i5-search');
  const defaultSearchUrl = defaultRoot?.dataset.searchUrl || '';
  const defaultShopsUrl = defaultRoot?.dataset.shopsUrl || '';

  document.querySelectorAll('[data-search-root]').forEach((root) => {
    initSearchWidget(root, {
      searchUrl: root.dataset.searchUrl || defaultSearchUrl,
      shopsUrl: root.dataset.shopsUrl || defaultShopsUrl,
    });
  });
});

function initSearchWidget(root, { searchUrl, shopsUrl }) {
  const input = root.querySelector('[data-search-input]');
  const resultsEl = root.querySelector('[data-search-results]');

  if (!input || !resultsEl || !searchUrl || !shopsUrl) return;

  let timer = null;
  let controller = null;

  const hideResults = () => {
    resultsEl.classList.add('hidden');
    resultsEl.innerHTML = '';
  };

  const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const renderLoading = (term) => {
    resultsEl.innerHTML = `
      <div class="px-5 py-6 text-center">
        <p class="font-body text-[13px] font-semibold text-muted">“${escapeHtml(term)}” aranıyor…</p>
      </div>`;
    resultsEl.classList.remove('hidden');
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
          <p class="mt-1 font-body text-[13px] text-muted">“${escapeHtml(term)}” için eşleşen ürün, kategori veya koleksiyon yok.</p>
          <a href="${shopsUrl}?q=${encodeURIComponent(term)}" class="mt-4 inline-flex items-center justify-center px-4 py-2.5 font-body text-[12px] font-bold uppercase tracking-[0.06em] border-[3px] border-ink bg-surface text-ink transition-colors hover:bg-hover">
            Tüm sonuçları gör
          </a>
        </div>`;
      resultsEl.classList.remove('hidden');
      return;
    }

    const typeLabel = (type) => {
      if (type === 'category') return 'Kategori';
      if (type === 'collection') return 'Koleksiyon';
      return 'Ürün';
    };

    const typeBadgeClass = (type) => {
      if (type === 'category') return 'bg-accent/10 text-accent';
      if (type === 'collection') return 'bg-action/10 text-action';
      return 'bg-bg text-muted';
    };

    const items = results.map((item) => {
      const badge = typeLabel(item.type);
      const subtitle = item.subtitle || item.code || '';
      const priceHtml = item.type === 'product' && item.price
        ? `<span class="shrink-0 font-body text-[12px] font-bold text-ink sm:text-[13px]">${escapeHtml(item.price)}</span>`
        : `<span class="shrink-0 font-body text-[10px] font-bold uppercase tracking-[0.06em] px-2 py-1 ${typeBadgeClass(item.type)}">${badge}</span>`;

      return `
      <a href="${item.url}" class="flex items-center gap-3 border-b border-ink/10 px-3 py-3 transition-colors last:border-b-0 hover:bg-hover sm:gap-4 sm:px-4">
        <div class="h-12 w-12 shrink-0 overflow-hidden border-[3px] border-ink bg-bg sm:h-14 sm:w-14">
          <img src="${item.image}" alt="" class="h-full w-full object-cover" loading="lazy">
        </div>
        <div class="min-w-0 flex-1">
          <p class="truncate font-body text-[13px] font-bold text-ink sm:text-[14px]">${escapeHtml(item.title)}</p>
          <p class="font-body text-[11px] text-muted sm:text-[12px]">${escapeHtml(subtitle)}</p>
        </div>
        ${priceHtml}
      </a>`;
    }).join('');

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

  const fetchResults = (term) => {
    if (controller) controller.abort();
    controller = new AbortController();
    renderLoading(term);

    fetch(`${searchUrl}?q=${encodeURIComponent(term)}`, {
      headers: { Accept: 'application/json' },
      signal: controller.signal,
    })
      .then((response) => {
        if (!response.ok) throw new Error('Arama başarısız');
        return response.json();
      })
      .then((payload) => renderResults(payload, term))
      .catch((error) => {
        if (error.name === 'AbortError') return;
        hideResults();
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

  input.addEventListener('focus', () => {
    const term = input.value.trim();
    if (term.length >= 2 && resultsEl.innerHTML === '') {
      fetchResults(term);
    }
  });

  root.addEventListener('click', (event) => {
    if (event.target === input) return;
  });

  document.addEventListener('click', (event) => {
    if (!root.contains(event.target)) {
      hideResults();
    }
  });
}
