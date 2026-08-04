<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('shipping_weight', 8, 3)->nullable()->after('stock_count');
            $table->unsignedInteger('shipping_length')->nullable()->after('shipping_weight');
            $table->unsignedInteger('shipping_width')->nullable()->after('shipping_length');
            $table->unsignedInteger('shipping_height')->nullable()->after('shipping_width');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_weight',
                'shipping_length',
                'shipping_width',
                'shipping_height',
            ]);
        });
    }
};
