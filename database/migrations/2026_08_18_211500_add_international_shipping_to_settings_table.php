<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('international_shipping_mode', 20)->default('paid')->after('shipping_free_limit');
            $table->decimal('international_shipping_fee', 10, 2)->default(0)->after('international_shipping_mode');
            $table->boolean('shipping_first_order_free')->default(false)->after('international_shipping_fee');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'international_shipping_mode',
                'international_shipping_fee',
                'shipping_first_order_free',
            ]);
        });
    }
};
