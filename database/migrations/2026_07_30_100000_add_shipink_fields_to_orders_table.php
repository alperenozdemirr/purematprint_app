<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipink_order_id')->nullable()->after('invoice_status');
            $table->string('shipink_shipment_id')->nullable()->after('shipink_order_id');
            $table->string('tracking_number')->nullable()->after('shipink_shipment_id');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->string('shipping_label_url')->nullable()->after('tracking_url');
            $table->timestamp('shipped_at')->nullable()->after('shipping_label_url');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('shipping_synced_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipink_order_id',
                'shipink_shipment_id',
                'tracking_number',
                'tracking_url',
                'shipping_label_url',
                'shipped_at',
                'delivered_at',
                'shipping_synced_at',
            ]);
        });
    }
};
