<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Http\Services\OrderEmailService;
use App\Http\Services\ShipinkShipmentService;
use App\Models\Order;
use Illuminate\Console\Command;

class ProcessOrderNotificationsCommand extends Command
{
    protected $signature = 'orders:process-notifications
                            {--order= : Belirli bir sipariş kodu için çalıştır (test)}
                            {--skip-sync : Shipink senkronizasyonunu atla}';

    protected $description = 'Sipariş durumlarını senkronize eder ve gerekli bildirim e-postalarını bir kez gönderir';

    public function handle(
        ShipinkShipmentService $shipinkService,
        OrderEmailService $emailService,
    ): int {
        $orderCode = $this->option('order');
        $skipSync = (bool) $this->option('skip-sync');

        $synced = 0;

        if (! $skipSync && $shipinkService->isConfigured()) {
            $syncQuery = Order::query()
                ->with('address')
                ->whereNotNull('shipink_shipment_id')
                ->whereNotIn('status', [OrderStatus::COMPLETED->value, OrderStatus::CANCELLED->value]);

            if ($orderCode) {
                $syncQuery->where('code', $orderCode);
            }

            foreach ($syncQuery->get() as $order) {
                if ($shipinkService->syncOrderShipment($order)) {
                    $synced++;
                }
            }
        }

        $emailQuery = Order::query()
            ->with(['user', 'address', 'payment'])
            ->where('status', '!=', OrderStatus::CANCELLED->value)
            ->where(function ($query) {
                $query->whereNull('confirmation_email_sent_at')
                    ->orWhere(function ($shippedQuery) {
                        $shippedQuery
                            ->where(function ($domesticQuery) {
                                $domesticQuery
                                    ->whereNotNull('carrier_picked_up_at')
                                    ->where(function ($resendQuery) {
                                        $resendQuery
                                            ->whereNull('shipped_email_shipment_id')
                                            ->orWhereColumn('shipped_email_shipment_id', '!=', 'shipink_shipment_id');
                                    })
                                    ->whereHas('address', function ($addressQuery) {
                                        $addressQuery->where('scope', 'domestic');
                                    });
                            })
                            ->orWhere(function ($internationalQuery) {
                                $internationalQuery
                                    ->whereNull('shipped_email_sent_at')
                                    ->whereIn('status', [
                                        OrderStatus::SHIPPED->value,
                                        OrderStatus::COMPLETED->value,
                                    ])
                                    ->whereHas('address', function ($addressQuery) {
                                        $addressQuery->where('scope', 'international');
                                    });
                            });
                    })
                    ->orWhere(function ($deliveredQuery) {
                        $deliveredQuery
                            ->whereNull('delivered_email_sent_at')
                            ->where(function ($completedQuery) {
                                $completedQuery
                                    ->where('status', OrderStatus::COMPLETED->value)
                                    ->orWhereNotNull('delivered_at');
                            });
                    });
            });

        if ($orderCode) {
            $emailQuery->where('code', $orderCode);
        }

        $sentConfirmation = 0;
        $sentShipped = 0;
        $sentDelivered = 0;

        foreach ($emailQuery->get() as $order) {
            $result = $emailService->processOrder($order);

            if ($result['confirmation']) {
                $sentConfirmation++;
            }

            if ($result['shipped']) {
                $sentShipped++;
            }

            if ($result['delivered']) {
                $sentDelivered++;
            }
        }

        $this->info("Shipink sync: {$synced} sipariş güncellendi.");
        $this->info("E-postalar — onay: {$sentConfirmation}, kargoya verildi: {$sentShipped}, teslim edildi: {$sentDelivered}.");

        return self::SUCCESS;
    }
}
