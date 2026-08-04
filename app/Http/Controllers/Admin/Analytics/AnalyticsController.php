<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAnalyticsService;
use App\Models\DailyAnalytic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(protected DailyAnalyticsService $analyticsService)
    {
    }

    public function index(Request $request): View
    {
        $timezone = (string) config('app.timezone', 'UTC');
        $today = Carbon::now($timezone)->startOfDay();

        $from = $this->parseDate($request->query('from'), $today->copy()->subDays(29), $timezone);
        $to = $this->parseDate($request->query('to'), $today->copy()->subDay(), $timezone);

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy(), $from->copy()];
        }

        // Grafik aralığı çok büyürse UI bozulmasın
        if ($from->diffInDays($to) > 366) {
            $from = $to->copy()->subDays(366);
        }

        $rows = DailyAnalytic::query()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('date')
            ->paginate(31)
            ->withQueryString();

        $period = DailyAnalytic::query()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->get();

        $summary = [
            'days' => $period->count(),
            'total_orders' => (int) $period->sum('total_orders'),
            'paid_orders' => (int) $period->sum('paid_orders'),
            'cancelled_orders' => (int) $period->sum('cancelled_orders'),
            'refunded_orders' => (int) $period->sum('refunded_orders'),
            'completed_orders' => (int) $period->sum('completed_orders'),
            'gross_revenue' => (float) $period->sum('gross_revenue'),
            'net_revenue' => (float) $period->sum('net_revenue'),
            'discount_total' => (float) $period->sum('discount_total'),
            'shipping_revenue' => (float) $period->sum('shipping_revenue'),
            'new_customers' => (int) $period->sum('new_customers'),
            'returning_customers' => (int) $period->sum('returning_customers'),
            'new_registrations' => (int) $period->sum('new_registrations'),
            'products_sold_quantity' => (int) $period->sum('products_sold_quantity'),
            'new_products' => (int) $period->sum('new_products'),
            'order_files_uploaded' => (int) $period->sum('order_files_uploaded'),
            'comments_created' => (int) $period->sum('comments_created'),
            'domestic_orders' => (int) $period->sum('domestic_orders'),
            'international_orders' => (int) $period->sum('international_orders'),
            'average_order_value' => $period->sum('paid_orders') > 0
                ? round((float) $period->sum('net_revenue') / (int) $period->sum('paid_orders'), 2)
                : 0.0,
        ];

        $charts = $this->analyticsService->buildPeriodCharts($period, $from, $to);

        $presets = [
            '7g' => [
                'label' => '7 gün',
                'from' => $today->copy()->subDays(7)->toDateString(),
                'to' => $today->copy()->subDay()->toDateString(),
            ],
            '30g' => [
                'label' => '30 gün',
                'from' => $today->copy()->subDays(29)->toDateString(),
                'to' => $today->copy()->subDay()->toDateString(),
            ],
            '90g' => [
                'label' => '90 gün',
                'from' => $today->copy()->subDays(89)->toDateString(),
                'to' => $today->copy()->subDay()->toDateString(),
            ],
            '12a' => [
                'label' => '12 ay',
                'from' => $today->copy()->subYear()->toDateString(),
                'to' => $today->copy()->subDay()->toDateString(),
            ],
        ];

        return view('admin.analytics', [
            'rows' => $rows,
            'summary' => $summary,
            'charts' => $charts,
            'presets' => $presets,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    public function recompute(Request $request): RedirectResponse
    {
        $timezone = (string) config('app.timezone', 'UTC');
        $today = Carbon::now($timezone)->startOfDay();

        $from = $this->parseDate($request->input('from'), $today->copy()->subDays(29), $timezone);
        $to = $this->parseDate($request->input('to'), $today->copy()->subDay(), $timezone);

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy(), $from->copy()];
        }

        if ($from->diffInDays($to) > 366) {
            return back()->with('error', 'Tek seferde en fazla 366 günlük aralık hesaplanabilir.');
        }

        $cursor = $from->copy();
        $count = 0;

        while ($cursor->lte($to)) {
            $this->analyticsService->computeAndStore($cursor);
            $count++;
            $cursor->addDay();
        }

        return redirect()
            ->route('admin.analytics', [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ])
            ->with('success', "{$count} gün için analiz yeniden hesaplandı.");
    }

    private function parseDate(mixed $value, Carbon $fallback, string $timezone): Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return $fallback->copy()->startOfDay();
        }

        try {
            return Carbon::parse($value, $timezone)->startOfDay();
        } catch (\Throwable) {
            return $fallback->copy()->startOfDay();
        }
    }
}
