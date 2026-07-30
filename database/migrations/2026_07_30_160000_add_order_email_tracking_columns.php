<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('confirmation_email_sent_at')->nullable()->after('shipping_synced_at');
            $table->timestamp('shipped_email_sent_at')->nullable()->after('confirmation_email_sent_at');
            $table->string('shipped_email_shipment_id')->nullable()->after('shipped_email_sent_at');
            $table->timestamp('delivered_email_sent_at')->nullable()->after('shipped_email_shipment_id');
            $table->timestamp('carrier_picked_up_at')->nullable()->after('delivered_email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'confirmation_email_sent_at',
                'shipped_email_sent_at',
                'shipped_email_shipment_id',
                'delivered_email_sent_at',
                'carrier_picked_up_at',
            ]);
        });
    }
};
