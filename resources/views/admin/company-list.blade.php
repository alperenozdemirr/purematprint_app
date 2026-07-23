@extends('admin.layout')
@section('title', 'Referans Firmalar')
@section('page_title', 'Referans Firmalar')
@section('breadcrumb', 'İçerik / Referans Firmalar')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Referans Firma Listesi</h2>
      <p class="font-body text-[13px] text-muted">
        Toplam <span class="font-bold text-ink">{{ $companies->count() }}</span> firma · Anasayfa kayan yazısında gösterilir
      </p>
      <p class="mt-1 font-body text-[12px] text-muted">Satırları sürükleyerek sıralayabilirsiniz.</p>
    </div>
    <a href="{{ route('admin.companyStorePage') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-5 py-3 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-accent-dark">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Yeni Firma
    </a>
  </div>

  <div id="company-sort-status" class="mb-4 hidden rounded-lg border border-accent/20 bg-accent/5 px-4 py-3 font-body text-[13px] font-semibold text-accent"></div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[640px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-4 [&_th]:py-3.5 [&_th]:font-body [&_th]:text-[11px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.08em] [&_th]:text-muted">
            <th class="w-12"></th>
            <th>Firma Adı</th>
            <th>Sıra</th>
            <th class="text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody id="company-sortable-list" class="divide-y divide-ink/8 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle">
          @forelse ($companies as $company)
            <tr class="transition-colors hover:bg-hover/60" data-company-id="{{ $company->id }}">
              <td>
                <button type="button" class="company-drag-handle flex h-9 w-9 cursor-grab items-center justify-center rounded-lg bg-cream text-muted transition-colors hover:bg-hover hover:text-ink active:cursor-grabbing" aria-label="Sürükle">
                  <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="1.5"/><circle cx="15" cy="7" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="17" r="1.5"/><circle cx="15" cy="17" r="1.5"/></svg>
                </button>
              </td>
              <td class="font-body text-[14px] font-bold text-ink">{{ $company->name }}</td>
              <td class="font-body text-[13px] text-muted" data-company-number>{{ $company->number }}</td>
              <td>
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.companyEditPage', $company->id) }}" aria-label="Düzenle" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-accent hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                  </a>
                  <a href="{{ route('admin.companyDelete', $company->id) }}" aria-label="Sil" onclick="return confirm('Bu firmayı silmek istediğinize emin misiniz?')" class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-ink transition-colors hover:bg-danger hover:text-on-dark">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-10 text-center font-body text-[14px] text-muted">Henüz referans firma bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
  (function () {
    const list = document.getElementById('company-sortable-list');
    const status = document.getElementById('company-sort-status');
    if (!list || typeof Sortable === 'undefined') return;

    const rows = list.querySelectorAll('[data-company-id]');
    if (!rows.length) return;

    const showStatus = (message, isError = false) => {
      if (!status) return;
      status.textContent = message;
      status.classList.remove('hidden', 'border-accent/20', 'bg-accent/5', 'text-accent', 'border-danger/20', 'bg-danger/5', 'text-danger');
      if (isError) {
        status.classList.add('border-danger/20', 'bg-danger/5', 'text-danger');
      } else {
        status.classList.add('border-accent/20', 'bg-accent/5', 'text-accent');
      }
    };

    const updateNumbers = () => {
      list.querySelectorAll('[data-company-id]').forEach((row, index) => {
        const numberCell = row.querySelector('[data-company-number]');
        if (numberCell) numberCell.textContent = String(index + 1);
      });
    };

    Sortable.create(list, {
      handle: '.company-drag-handle',
      animation: 150,
      ghostClass: 'bg-accent/10',
      onEnd: async () => {
        const order = [...list.querySelectorAll('[data-company-id]')].map((row) => row.dataset.companyId);
        updateNumbers();

        try {
          const response = await fetch(@json(route('admin.companyReorder')), {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': @json(csrf_token()),
            },
            body: JSON.stringify({ order }),
          });

          if (!response.ok) {
            throw new Error('Sıralama kaydedilemedi.');
          }

          showStatus('Sıralama kaydedildi.');
        } catch (error) {
          showStatus(error.message || 'Sıralama kaydedilemedi.', true);
        }
      },
    });
  })();
</script>
@endsection
