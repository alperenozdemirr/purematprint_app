<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserType;
use App\Models\Comment;
use App\Models\DailyAnalytic;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DailyAnalyticsService
{
    /**
     * Belirtilen gün için metrikleri hesaplar ve daily_analytics satırına yazar (upsert).
     */
    public function computeAndStore(CarbonInterface $date): DailyAnalytic
    {
        $metrics = $this->calculate($date);

        return DailyAnalytic::query()->updateOrCreate(
            ['date' => $date->toDateString()],
            $metrics,
        );
    }

    /**
     * @return array<string, int|float|string>
     */
    public function calculate(CarbonInterface $date): array
    {
        $day = Carbon::parse($date->toDateString(), config('app.timezone'));
        $start = $day->copy()->startOfDay();
        $end = $day->copy()->endOfDay();

        $orders = Order::query()
            ->with(['payment', 'address'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $paidOrders = $orders->filter(function (Order $order): bool {
            return $order->payment?->status === PaymentStatus::COMPLETED
                && $order->status !== OrderStatus::CANCELLED;
        });

        $grossRevenue = round((float) $paidOrders->sum(fn (Order $o) => (float) $o->subtotal), 2);
        $netRevenue = round((float) $paidOrders->sum(fn (Order $o) => (float) $o->total), 2);
        $discountTotal = round((float) $paidOrders->sum(fn (Order $o) => (float) $o->discount_amount), 2);
        $shippingRevenue = round((float) $paidOrders->sum(fn (Order $o) => (float) $o->shipping_price), 2);
        $paidCount = $paidOrders->count();

        $cancelledOrders = Order::query()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('cancelled_at', [$start, $end])
                    ->orWhere(function ($legacy) use ($start, $end) {
                        $legacy->whereNull('cancelled_at')
                            ->where('status', OrderStatus::CANCELLED->value)
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            })
            ->count();

        $refundedOrders = Payment::query()
            ->where('status', PaymentStatus::REFUNDED->value)
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $completedOrders = Order::query()
            ->where('status', OrderStatus::COMPLETED->value)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('delivered_at', [$start, $end])
                    ->orWhere(function ($inner) use ($start, $end) {
                        $inner->whereNull('delivered_at')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            })
            ->count();

        $orderIds = $paidOrders->pluck('id');
        $productsSoldQuantity = $orderIds->isEmpty()
            ? 0
            : (int) OrderDetail::query()->whereIn('order_id', $orderIds)->sum('quantity');

        [$newCustomers, $returningCustomers] = $this->customerCounts($paidOrders, $start);

        $domesticOrders = $orders->filter(
            fn (Order $o) => $o->address?->isDomestic() ?? true
        )->count();
        $internationalOrders = $orders->count() - $domesticOrders;

        return [
            'total_orders' => $orders->count(),
            'paid_orders' => $paidCount,
            'cancelled_orders' => $cancelledOrders,
            'refunded_orders' => $refundedOrders,
            'completed_orders' => $completedOrders,
            'gross_revenue' => $grossRevenue,
            'net_revenue' => $netRevenue,
            'discount_total' => $discountTotal,
            'shipping_revenue' => $shippingRevenue,
            'average_order_value' => $paidCount > 0 ? round($netRevenue / $paidCount, 2) : 0,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'new_registrations' => User::query()
                ->where('type', UserType::USER->value)
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'products_sold_quantity' => $productsSoldQuantity,
            'new_products' => Product::query()
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'order_files_uploaded' => File::query()
                ->where('content_type', ContentType::ORDER_FILE->value)
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'comments_created' => Comment::query()
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'domestic_orders' => $domesticOrders,
            'international_orders' => $internationalOrders,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Order>  $paidOrders
     * @return array{0: int, 1: int}
     */
    private function customerCounts($paidOrders, CarbonInterface $dayStart): array
    {
        $userIds = $paidOrders->pluck('user_id')->unique()->filter()->values();

        if ($userIds->isEmpty()) {
            return [0, 0];
        }

        $new = 0;
        $returning = 0;

        foreach ($userIds as $userId) {
            $hadEarlierPaidOrder = Order::query()
                ->where('user_id', $userId)
                ->where('created_at', '<', $dayStart)
                ->where('status', '!=', OrderStatus::CANCELLED->value)
                ->whereHas('payment', fn ($q) => $q->where('status', PaymentStatus::COMPLETED->value))
                ->exists();

            if ($hadEarlierPaidOrder) {
                $returning++;
            } else {
                $new++;
            }
        }

        return [$new, $returning];
    }

    /**
     * Mevcut günlük kayıtlardan günlük / haftalık / aylık grafik ve özet veri üretir.
     *
     * @param  Collection<int, DailyAnalytic>  $period
     * @return array{
     *   daily: array{labels: list<string>, series: array<string, list<float|int>>, rows: list<array<string, mixed>>},
     *   weekly: array{labels: list<string>, series: array<string, list<float|int>>, rows: list<array<string, mixed>>},
     *   monthly: array{labels: list<string>, series: array<string, list<float|int>>, rows: list<array<string, mixed>>}
     * }
     */
    public function buildPeriodCharts(Collection $period, CarbonInterface $from, CarbonInterface $to): array
    {
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('tr');

        $byDate = $period->keyBy(fn (DailyAnalytic $row) => $row->date->toDateString());

        $dailyRows = [];
        $cursor = Carbon::parse($from->toDateString())->startOfDay();
        $end = Carbon::parse($to->toDateString())->startOfDay();

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $row = $byDate->get($key);
            $dailyRows[] = $this->normalizeBucket(
                label: $cursor->format('d.m'),
                fullLabel: $cursor->format('d.m.Y'),
                key: $key,
                source: $row,
            );
            $cursor->addDay();
        }

        $weeklyBuckets = [];
        foreach ($dailyRows as $day) {
            $date = Carbon::parse($day['key']);
            $weekKey = $date->isoFormat('GGGG-[W]WW');
            if (! isset($weeklyBuckets[$weekKey])) {
                $weekStart = $date->copy()->startOfWeek();
                $weekEnd = $date->copy()->endOfWeek();
                $weeklyBuckets[$weekKey] = [
                    'key' => $weekKey,
                    'label' => $weekStart->format('d.m').'–'.$weekEnd->format('d.m'),
                    'full_label' => $weekStart->format('d.m.Y').' – '.$weekEnd->format('d.m.Y'),
                    'rows' => [],
                ];
            }
            $weeklyBuckets[$weekKey]['rows'][] = $day;
        }

        $monthlyBuckets = [];
        foreach ($dailyRows as $day) {
            $date = Carbon::parse($day['key']);
            $monthKey = $date->format('Y-m');
            if (! isset($monthlyBuckets[$monthKey])) {
                $monthlyBuckets[$monthKey] = [
                    'key' => $monthKey,
                    'label' => $date->translatedFormat('M Y'),
                    'full_label' => $date->translatedFormat('F Y'),
                    'rows' => [],
                ];
            }
            $monthlyBuckets[$monthKey]['rows'][] = $day;
        }

        $weeklyRows = array_map(
            fn (array $bucket) => $this->aggregateBucket($bucket),
            array_values($weeklyBuckets),
        );
        $monthlyRows = array_map(
            fn (array $bucket) => $this->aggregateBucket($bucket),
            array_values($monthlyBuckets),
        );

        $payload = [
            'daily' => $this->toChartPayload($dailyRows),
            'weekly' => $this->toChartPayload($weeklyRows),
            'monthly' => $this->toChartPayload($monthlyRows),
        ];

        Carbon::setLocale($previousLocale);

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeBucket(string $label, string $fullLabel, string $key, ?DailyAnalytic $source): array
    {
        $paid = (int) ($source?->paid_orders ?? 0);
        $net = (float) ($source?->net_revenue ?? 0);

        return [
            'key' => $key,
            'label' => $label,
            'full_label' => $fullLabel,
            'total_orders' => (int) ($source?->total_orders ?? 0),
            'paid_orders' => $paid,
            'cancelled_orders' => (int) ($source?->cancelled_orders ?? 0),
            'refunded_orders' => (int) ($source?->refunded_orders ?? 0),
            'completed_orders' => (int) ($source?->completed_orders ?? 0),
            'gross_revenue' => (float) ($source?->gross_revenue ?? 0),
            'net_revenue' => $net,
            'discount_total' => (float) ($source?->discount_total ?? 0),
            'shipping_revenue' => (float) ($source?->shipping_revenue ?? 0),
            'average_order_value' => $paid > 0 ? round($net / $paid, 2) : 0.0,
            'new_customers' => (int) ($source?->new_customers ?? 0),
            'returning_customers' => (int) ($source?->returning_customers ?? 0),
            'new_registrations' => (int) ($source?->new_registrations ?? 0),
            'products_sold_quantity' => (int) ($source?->products_sold_quantity ?? 0),
            'order_files_uploaded' => (int) ($source?->order_files_uploaded ?? 0),
            'comments_created' => (int) ($source?->comments_created ?? 0),
            'domestic_orders' => (int) ($source?->domestic_orders ?? 0),
            'international_orders' => (int) ($source?->international_orders ?? 0),
            'days_count' => $source ? 1 : 0,
        ];
    }

    /**
     * @param  array{key: string, label: string, full_label: string, rows: list<array<string, mixed>>}  $bucket
     * @return array<string, mixed>
     */
    private function aggregateBucket(array $bucket): array
    {
        $rows = $bucket['rows'];
        $paid = (int) array_sum(array_column($rows, 'paid_orders'));
        $net = (float) array_sum(array_column($rows, 'net_revenue'));

        return [
            'key' => $bucket['key'],
            'label' => $bucket['label'],
            'full_label' => $bucket['full_label'],
            'total_orders' => (int) array_sum(array_column($rows, 'total_orders')),
            'paid_orders' => $paid,
            'cancelled_orders' => (int) array_sum(array_column($rows, 'cancelled_orders')),
            'refunded_orders' => (int) array_sum(array_column($rows, 'refunded_orders')),
            'completed_orders' => (int) array_sum(array_column($rows, 'completed_orders')),
            'gross_revenue' => round((float) array_sum(array_column($rows, 'gross_revenue')), 2),
            'net_revenue' => round($net, 2),
            'discount_total' => round((float) array_sum(array_column($rows, 'discount_total')), 2),
            'shipping_revenue' => round((float) array_sum(array_column($rows, 'shipping_revenue')), 2),
            'average_order_value' => $paid > 0 ? round($net / $paid, 2) : 0.0,
            'new_customers' => (int) array_sum(array_column($rows, 'new_customers')),
            'returning_customers' => (int) array_sum(array_column($rows, 'returning_customers')),
            'new_registrations' => (int) array_sum(array_column($rows, 'new_registrations')),
            'products_sold_quantity' => (int) array_sum(array_column($rows, 'products_sold_quantity')),
            'order_files_uploaded' => (int) array_sum(array_column($rows, 'order_files_uploaded')),
            'comments_created' => (int) array_sum(array_column($rows, 'comments_created')),
            'domestic_orders' => (int) array_sum(array_column($rows, 'domestic_orders')),
            'international_orders' => (int) array_sum(array_column($rows, 'international_orders')),
            'days_count' => (int) array_sum(array_column($rows, 'days_count')),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array{labels: list<string>, series: array<string, list<float|int>>, rows: list<array<string, mixed>>}
     */
    private function toChartPayload(array $rows): array
    {
        return [
            'labels' => array_values(array_map(fn (array $row) => (string) $row['label'], $rows)),
            'series' => [
                'net_revenue' => array_values(array_map(fn (array $row) => (float) $row['net_revenue'], $rows)),
                'gross_revenue' => array_values(array_map(fn (array $row) => (float) $row['gross_revenue'], $rows)),
                'paid_orders' => array_values(array_map(fn (array $row) => (int) $row['paid_orders'], $rows)),
                'cancelled_orders' => array_values(array_map(fn (array $row) => (int) $row['cancelled_orders'], $rows)),
                'new_customers' => array_values(array_map(fn (array $row) => (int) $row['new_customers'], $rows)),
                'returning_customers' => array_values(array_map(fn (array $row) => (int) $row['returning_customers'], $rows)),
                'products_sold_quantity' => array_values(array_map(fn (array $row) => (int) $row['products_sold_quantity'], $rows)),
                'average_order_value' => array_values(array_map(fn (array $row) => (float) $row['average_order_value'], $rows)),
            ],
            'rows' => array_values($rows),
        ];
    }
}
