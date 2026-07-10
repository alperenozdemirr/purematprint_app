<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->where('status', 'payment_pending')
            ->update(['status' => OrderStatus::PREPARING->value]);

        $orders = DB::table('orders')->orderBy('id')->get();
        $usedCodes = [];

        foreach ($orders as $order) {
            if (! preg_match('/^ORD-(\d{8})(?:-\d+)?$/', $order->code, $matches)) {
                continue;
            }

            $newCode = 'ORD-' . $matches[1];

            while (
                in_array($newCode, $usedCodes, true)
                || DB::table('orders')->where('code', $newCode)->where('id', '!=', $order->id)->exists()
            ) {
                $newCode = 'ORD-' . $matches[1] . random_int(1000, 9999);
            }

            if ($newCode !== $order->code) {
                DB::table('orders')->where('id', $order->id)->update(['code' => $newCode]);
            }

            $usedCodes[] = $newCode;
        }

        DB::statement("ALTER TABLE orders MODIFY status ENUM('preparing','shipped','completed','cancelled') NOT NULL DEFAULT 'preparing'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('payment_pending','preparing','shipped','completed','cancelled') NOT NULL DEFAULT 'preparing'");
    }
};
