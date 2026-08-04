<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Services\DailyAnalyticsService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ComputeDailyAnalyticsCommand extends Command
{
    protected $signature = 'analytics:compute-daily
                            {--date= : Hesaplanacak gün (Y-m-d). Boşsa dün}
                            {--from= : Aralık başlangıcı (Y-m-d)}
                            {--to= : Aralık bitişi (Y-m-d)}';

    protected $description = 'Günlük satış / sipariş analiz metriklerini hesaplar ve kaydeder';

    public function handle(DailyAnalyticsService $analyticsService): int
    {
        $timezone = (string) config('app.timezone', 'UTC');

        if ($this->option('from') || $this->option('to')) {
            $from = Carbon::parse((string) ($this->option('from') ?: $this->option('to')), $timezone)->startOfDay();
            $to = Carbon::parse((string) ($this->option('to') ?: $this->option('from')), $timezone)->startOfDay();

            if ($from->gt($to)) {
                [$from, $to] = [$to, $from];
            }

            $cursor = $from->copy();
            $count = 0;

            while ($cursor->lte($to)) {
                $row = $analyticsService->computeAndStore($cursor);
                $this->line($row->date->toDateString().' → sipariş: '.$row->total_orders.', net: '.$row->net_revenue);
                $count++;
                $cursor->addDay();
            }

            $this->info("Tamamlandı: {$count} gün.");

            return self::SUCCESS;
        }

        $dateOption = $this->option('date');
        $date = $dateOption
            ? Carbon::parse((string) $dateOption, $timezone)->startOfDay()
            : Carbon::now($timezone)->subDay()->startOfDay();

        $row = $analyticsService->computeAndStore($date);

        $this->info(sprintf(
            '%s kaydedildi | sipariş: %d | ödenen: %d | iptal: %d | net ciro: %s',
            $row->date->toDateString(),
            $row->total_orders,
            $row->paid_orders,
            $row->cancelled_orders,
            number_format((float) $row->net_revenue, 2, ',', '.'),
        ));

        return self::SUCCESS;
    }
}
