<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Http\Services\ShipinkShipmentService;
use App\Models\Order;
use Illuminate\Console\Command;

class SyncShipinkShipmentsCommand extends Command
{
    protected $signature = 'shipink:sync-shipments';

    protected $description = 'Sync domestic Shipink shipment statuses and update orders';

    public function handle(ShipinkShipmentService $shipinkService): int
    {
        if (! $shipinkService->isConfigured()) {
            $this->warn('Shipink is not configured.');

            return self::SUCCESS;
        }

        $orders = Order::query()
            ->with('address')
            ->whereNotNull('shipink_shipment_id')
            ->whereNotIn('status', [OrderStatus::COMPLETED->value, OrderStatus::CANCELLED->value])
            ->get();

        $synced = 0;

        foreach ($orders as $order) {
            if ($shipinkService->syncOrderShipment($order)) {
                $synced++;
            }
        }

        $this->info("Synced {$synced} shipment(s).");

        return self::SUCCESS;
    }
}
