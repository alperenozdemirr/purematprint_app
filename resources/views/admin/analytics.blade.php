@extends('admin.layout')
@section('title', 'Günlük Analiz')
@section('page_title', 'Günlük Analiz')
@section('breadcrumb', 'Satış / Analiz')

@section('content')
  <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <h2 class="font-heading text-[22px] font-bold leading-tight text-ink">Satış Analizi</h2>
      <p class="font-body text-[13px] text-muted">
        {{ \Illuminate\Support\Carbon::parse($from)->format('d.m.Y') }}
        —
        {{ \Illuminate\Support\Carbon::parse($to)->format('d.m.Y') }}
        ·
        <span class="font-bold text-ink">{{ $summary['days'] }}</span> gün kaydı
        · günlük veriden haftalık / aylık hesaplanır
      </p>
    </div>
    <div class="flex flex-wrap gap-2">
      @foreach ($presets as $preset)
        <a href="{{ route('admin.analytics', ['from' => $preset['from'], 'to' => $preset['to']]) }}"
           class="rounded-lg border border-ink/10 bg-surface px-3 py-1.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] text-ink transition-colors hover:bg-hover {{ $from === $preset['from'] && $to === $preset['to'] ? 'border-accent bg-accent/10 text-accent' : '' }}">
          {{ $preset['label'] }}
        </a>
      @endforeach
    </div>
  </div>

  <form action="{{ route('admin.analytics') }}" method="get" class="mb-4 rounded-xl bg-surface p-4 shadow-card">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto_auto]">
      <div>
        <label for="from" class="mb-1.5 block font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Başlangıç</label>
        <input type="date" id="from" name="from" value="{{ $from }}" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent">
      </div>
      <div>
        <label for="to" class="mb-1.5 block font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Bitiş</label>
        <input type="date" id="to" name="to" value="{{ $to }}" class="w-full rounded-lg border border-ink/10 bg-cream px-3.5 py-2.5 font-body text-[14px] text-ink outline-none focus:border-accent">
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full rounded-lg bg-ink px-5 py-2.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-on-dark transition-colors hover:bg-action">Filtrele</button>
      </div>
      <div class="flex items-end">
        <button type="submit" form="analytics-recompute-form" class="w-full rounded-lg border border-ink/15 bg-cream px-5 py-2.5 font-body text-[13px] font-bold uppercase tracking-[0.06em] text-ink transition-colors hover:bg-hover">Yeniden Hesapla</button>
      </div>
    </div>
  </form>

  <form id="analytics-recompute-form" action="{{ route('admin.analyticsRecompute') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="from" value="{{ $from }}">
    <input type="hidden" name="to" value="{{ $to }}">
  </form>

  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-heading text-[28px] font-bold leading-none text-ink">{{ $summary['total_orders'] }}</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Toplam Sipariş</p>
      <p class="mt-1 font-body text-[11px] text-muted">Ödenen: {{ $summary['paid_orders'] }} · İptal: {{ $summary['cancelled_orders'] }}</p>
    </div>
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-heading text-[28px] font-bold leading-none text-ink">{{ number_format($summary['net_revenue'], 0, ',', '.') }}₺</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Net Ciro</p>
      <p class="mt-1 font-body text-[11px] text-muted">Brüt: {{ number_format($summary['gross_revenue'], 0, ',', '.') }}₺</p>
    </div>
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-heading text-[28px] font-bold leading-none text-ink">{{ number_format($summary['average_order_value'], 0, ',', '.') }}₺</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Ort. Sepet</p>
      <p class="mt-1 font-body text-[11px] text-muted">Satılan adet: {{ $summary['products_sold_quantity'] }}</p>
    </div>
    <div class="rounded-xl bg-surface p-5 shadow-card">
      <p class="font-heading text-[28px] font-bold leading-none text-ink">{{ $summary['new_customers'] }}</p>
      <p class="mt-1.5 font-body text-[13px] font-medium text-muted">Yeni Müşteri</p>
      <p class="mt-1 font-body text-[11px] text-muted">Geri dönen: {{ $summary['returning_customers'] }} · Kayıt: {{ $summary['new_registrations'] }}</p>
    </div>
  </div>

  <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Teslim</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ $summary['completed_orders'] }}</p>
    </div>
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">İade</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ $summary['refunded_orders'] }}</p>
    </div>
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">İndirim</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ number_format($summary['discount_total'], 0, ',', '.') }}₺</p>
    </div>
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Kargo Geliri</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ number_format($summary['shipping_revenue'], 0, ',', '.') }}₺</p>
    </div>
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Sipariş Dosyası</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ $summary['order_files_uploaded'] }}</p>
    </div>
    <div class="rounded-xl bg-surface px-4 py-3 shadow-card">
      <p class="font-body text-[11px] font-bold uppercase tracking-[0.06em] text-muted">Yİ / YD</p>
      <p class="mt-1 font-heading text-[20px] font-bold text-ink">{{ $summary['domestic_orders'] }} / {{ $summary['international_orders'] }}</p>
    </div>
  </div>

  {{-- Grafik paneli --}}
  <section class="mb-6 overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="flex flex-col gap-3 border-b border-ink/10 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="font-heading text-[17px] font-bold text-ink">Grafikler</h3>
        <p class="font-body text-[12px] text-muted">Günlük kayıtlardan anlık hesaplanan görünüm</p>
      </div>
      <div class="inline-flex rounded-lg border border-ink/10 bg-cream p-1" role="tablist">
        <button type="button" data-analytics-tab="daily" class="analytics-tab rounded-md px-3 py-1.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] transition-colors">Günlük</button>
        <button type="button" data-analytics-tab="weekly" class="analytics-tab rounded-md px-3 py-1.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] transition-colors">Haftalık</button>
        <button type="button" data-analytics-tab="monthly" class="analytics-tab rounded-md px-3 py-1.5 font-body text-[12px] font-bold uppercase tracking-[0.04em] transition-colors">Aylık</button>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 p-5 xl:grid-cols-2">
      <div>
        <p class="mb-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Ciro</p>
        <div class="relative h-[280px]">
          <canvas id="analytics-revenue-chart"></canvas>
        </div>
      </div>
      <div>
        <p class="mb-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Siparişler</p>
        <div class="relative h-[280px]">
          <canvas id="analytics-orders-chart"></canvas>
        </div>
      </div>
      <div>
        <p class="mb-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Müşteriler</p>
        <div class="relative h-[280px]">
          <canvas id="analytics-customers-chart"></canvas>
        </div>
      </div>
      <div>
        <p class="mb-3 font-body text-[12px] font-bold uppercase tracking-[0.06em] text-muted">Ort. Sepet & Satılan Adet</p>
        <div class="relative h-[280px]">
          <canvas id="analytics-basket-chart"></canvas>
        </div>
      </div>
    </div>
  </section>

  {{-- Haftalık / Aylık özet tablolar --}}
  <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Haftalık Özet</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[520px] border-collapse text-left">
          <thead>
            <tr class="bg-cream/60 [&_th]:px-3 [&_th]:py-3 [&_th]:font-body [&_th]:text-[10px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.06em] [&_th]:text-muted">
              <th>Hafta</th>
              <th>Ödenen</th>
              <th>Net</th>
              <th>Ort.</th>
              <th>Yeni</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/8 [&_td]:px-3 [&_td]:py-2.5 [&_td]:font-body [&_td]:text-[13px]">
            @forelse (array_reverse($charts['weekly']['rows']) as $week)
              <tr class="hover:bg-hover/60">
                <td class="font-semibold text-ink" title="{{ $week['full_label'] }}">{{ $week['label'] }}</td>
                <td>{{ $week['paid_orders'] }}</td>
                <td class="font-bold">{{ number_format((float) $week['net_revenue'], 0, ',', '.') }}₺</td>
                <td>{{ number_format((float) $week['average_order_value'], 0, ',', '.') }}₺</td>
                <td>{{ $week['new_customers'] }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-5 py-8 text-center text-muted">Haftalık veri yok</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-surface shadow-card">
      <div class="border-b border-ink/10 px-5 py-4">
        <h3 class="font-heading text-[16px] font-bold text-ink">Aylık Özet</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[520px] border-collapse text-left">
          <thead>
            <tr class="bg-cream/60 [&_th]:px-3 [&_th]:py-3 [&_th]:font-body [&_th]:text-[10px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.06em] [&_th]:text-muted">
              <th>Ay</th>
              <th>Ödenen</th>
              <th>Net</th>
              <th>Ort.</th>
              <th>Yeni</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/8 [&_td]:px-3 [&_td]:py-2.5 [&_td]:font-body [&_td]:text-[13px]">
            @forelse (array_reverse($charts['monthly']['rows']) as $month)
              <tr class="hover:bg-hover/60">
                <td class="font-semibold text-ink" title="{{ $month['full_label'] }}">{{ $month['label'] }}</td>
                <td>{{ $month['paid_orders'] }}</td>
                <td class="font-bold">{{ number_format((float) $month['net_revenue'], 0, ',', '.') }}₺</td>
                <td>{{ number_format((float) $month['average_order_value'], 0, ',', '.') }}₺</td>
                <td>{{ $month['new_customers'] }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-5 py-8 text-center text-muted">Aylık veri yok</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <div class="overflow-hidden rounded-xl bg-surface shadow-card">
    <div class="border-b border-ink/10 px-5 py-4">
      <h3 class="font-heading text-[16px] font-bold text-ink">Günlük Detay</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[1400px] border-collapse text-left">
        <thead>
          <tr class="bg-cream/60 [&_th]:px-3 [&_th]:py-3 [&_th]:font-body [&_th]:text-[10px] [&_th]:font-bold [&_th]:uppercase [&_th]:tracking-[0.06em] [&_th]:text-muted">
            <th>Tarih</th>
            <th>Sipariş</th>
            <th>Ödenen</th>
            <th>İptal</th>
            <th>İade</th>
            <th>Teslim</th>
            <th>Brüt</th>
            <th>Net</th>
            <th>Ort. Sepet</th>
            <th>Yeni Müş.</th>
            <th>Geri Dön.</th>
            <th>Adet</th>
            <th>Dosya</th>
            <th>Yİ / YD</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/8 [&_td]:px-3 [&_td]:py-3 [&_td]:align-middle [&_td]:font-body [&_td]:text-[13px]">
          @forelse ($rows as $row)
            <tr class="transition-colors hover:bg-hover/60">
              <td class="font-semibold text-ink">{{ $row->date->format('d.m.Y') }}</td>
              <td class="text-ink">{{ $row->total_orders }}</td>
              <td class="text-ink">{{ $row->paid_orders }}</td>
              <td class="text-ink">{{ $row->cancelled_orders }}</td>
              <td class="text-ink">{{ $row->refunded_orders }}</td>
              <td class="text-ink">{{ $row->completed_orders }}</td>
              <td class="font-semibold text-ink">{{ number_format((float) $row->gross_revenue, 0, ',', '.') }}₺</td>
              <td class="font-bold text-ink">{{ number_format((float) $row->net_revenue, 0, ',', '.') }}₺</td>
              <td class="text-ink">{{ number_format((float) $row->average_order_value, 0, ',', '.') }}₺</td>
              <td class="text-ink">{{ $row->new_customers }}</td>
              <td class="text-ink">{{ $row->returning_customers }}</td>
              <td class="text-ink">{{ $row->products_sold_quantity }}</td>
              <td class="text-ink">{{ $row->order_files_uploaded }}</td>
              <td class="text-muted">{{ $row->domestic_orders }} / {{ $row->international_orders }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="14" class="px-5 py-10 text-center font-body text-[14px] text-muted">
                Bu aralıkta kayıt yok. “Yeniden Hesapla” ile geçmiş günleri oluşturabilirsiniz.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($rows->hasPages())
      <div class="border-t border-ink/10 px-5 py-4">
        {{ $rows->links() }}
      </div>
    @endif
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
  const chartData = @json($charts);
  const colors = {
    accent: '#354e9c',
    accentSoft: 'rgba(53, 78, 156, 0.18)',
    ink: '#1a1a1a',
    inkSoft: 'rgba(26, 26, 26, 0.12)',
    success: '#2f7a4d',
    successSoft: 'rgba(47, 122, 77, 0.18)',
    danger: '#b61d0f',
    dangerSoft: 'rgba(182, 29, 15, 0.16)',
    warning: '#c4772a',
    muted: '#5e5a54',
  };

  const money = (value) => new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    maximumFractionDigits: 0,
  }).format(value || 0);

  const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: {
        labels: {
          boxWidth: 12,
          boxHeight: 12,
          font: { family: 'IBM Plex Sans', size: 12, weight: '600' },
          color: colors.muted,
        },
      },
      tooltip: {
        backgroundColor: '#2a2826',
        titleFont: { family: 'IBM Plex Sans', size: 12, weight: '700' },
        bodyFont: { family: 'IBM Plex Sans', size: 12 },
        padding: 10,
      },
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { color: colors.muted, font: { family: 'IBM Plex Sans', size: 11 } },
      },
      y: {
        beginAtZero: true,
        grid: { color: colors.inkSoft },
        ticks: { color: colors.muted, font: { family: 'IBM Plex Sans', size: 11 } },
      },
    },
  };

  let revenueChart;
  let ordersChart;
  let customersChart;
  let basketChart;
  let activeTab = 'daily';

  const destroyCharts = () => {
    [revenueChart, ordersChart, customersChart, basketChart].forEach((chart) => {
      if (chart) chart.destroy();
    });
  };

  const renderCharts = (tab) => {
    const pack = chartData[tab];
    if (!pack) return;

    destroyCharts();

    const labels = pack.labels || [];
    const series = pack.series || {};

    revenueChart = new Chart(document.getElementById('analytics-revenue-chart'), {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'Net Ciro',
            data: series.net_revenue || [],
            borderColor: colors.accent,
            backgroundColor: colors.accentSoft,
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointRadius: labels.length > 40 ? 0 : 3,
            pointHoverRadius: 5,
          },
          {
            label: 'Brüt Ciro',
            data: series.gross_revenue || [],
            borderColor: colors.ink,
            backgroundColor: 'transparent',
            borderDash: [5, 4],
            tension: 0.35,
            borderWidth: 2,
            pointRadius: 0,
          },
        ],
      },
      options: {
        ...baseOptions,
        plugins: {
          ...baseOptions.plugins,
          tooltip: {
            ...baseOptions.plugins.tooltip,
            callbacks: {
              label: (ctx) => `${ctx.dataset.label}: ${money(ctx.parsed.y)}`,
            },
          },
        },
        scales: {
          ...baseOptions.scales,
          y: {
            ...baseOptions.scales.y,
            ticks: {
              ...baseOptions.scales.y.ticks,
              callback: (value) => money(value),
            },
          },
        },
      },
    });

    ordersChart = new Chart(document.getElementById('analytics-orders-chart'), {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Ödenen',
            data: series.paid_orders || [],
            backgroundColor: colors.accent,
            borderRadius: 6,
            maxBarThickness: 28,
          },
          {
            label: 'İptal',
            data: series.cancelled_orders || [],
            backgroundColor: colors.danger,
            borderRadius: 6,
            maxBarThickness: 28,
          },
        ],
      },
      options: {
        ...baseOptions,
        scales: {
          ...baseOptions.scales,
          x: { ...baseOptions.scales.x, stacked: false },
          y: { ...baseOptions.scales.y, ticks: { ...baseOptions.scales.y.ticks, precision: 0 } },
        },
      },
    });

    customersChart = new Chart(document.getElementById('analytics-customers-chart'), {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Yeni Müşteri',
            data: series.new_customers || [],
            backgroundColor: colors.success,
            borderRadius: 6,
            maxBarThickness: 28,
          },
          {
            label: 'Geri Dönen',
            data: series.returning_customers || [],
            backgroundColor: colors.warning,
            borderRadius: 6,
            maxBarThickness: 28,
          },
        ],
      },
      options: {
        ...baseOptions,
        scales: {
          ...baseOptions.scales,
          y: { ...baseOptions.scales.y, ticks: { ...baseOptions.scales.y.ticks, precision: 0 } },
        },
      },
    });

    basketChart = new Chart(document.getElementById('analytics-basket-chart'), {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'Ort. Sepet (₺)',
            data: series.average_order_value || [],
            borderColor: colors.accent,
            backgroundColor: colors.accentSoft,
            yAxisID: 'y',
            tension: 0.35,
            borderWidth: 2.5,
            fill: true,
            pointRadius: labels.length > 40 ? 0 : 3,
          },
          {
            label: 'Satılan Adet',
            data: series.products_sold_quantity || [],
            borderColor: colors.success,
            backgroundColor: colors.successSoft,
            yAxisID: 'y1',
            tension: 0.35,
            borderWidth: 2,
            fill: false,
            pointRadius: labels.length > 40 ? 0 : 3,
          },
        ],
      },
      options: {
        ...baseOptions,
        scales: {
          x: baseOptions.scales.x,
          y: {
            ...baseOptions.scales.y,
            position: 'left',
            ticks: {
              ...baseOptions.scales.y.ticks,
              callback: (value) => money(value),
            },
          },
          y1: {
            beginAtZero: true,
            position: 'right',
            grid: { drawOnChartArea: false },
            ticks: {
              color: colors.muted,
              font: { family: 'IBM Plex Sans', size: 11 },
              precision: 0,
            },
          },
        },
      },
    });
  };

  const setActiveTab = (tab) => {
    activeTab = tab;
    document.querySelectorAll('[data-analytics-tab]').forEach((btn) => {
      const isActive = btn.dataset.analyticsTab === tab;
      btn.classList.toggle('bg-accent', isActive);
      btn.classList.toggle('text-on-dark', isActive);
      btn.classList.toggle('text-muted', !isActive);
    });
    renderCharts(tab);
  };

  document.querySelectorAll('[data-analytics-tab]').forEach((btn) => {
    btn.addEventListener('click', () => setActiveTab(btn.dataset.analyticsTab));
  });

  // Aralık uzunsa varsayılanı haftalık/aylık seç
  const dayCount = (chartData.daily?.labels || []).length;
  if (dayCount > 90) {
    setActiveTab('monthly');
  } else if (dayCount > 45) {
    setActiveTab('weekly');
  } else {
    setActiveTab('daily');
  }
})();
</script>
@endpush
