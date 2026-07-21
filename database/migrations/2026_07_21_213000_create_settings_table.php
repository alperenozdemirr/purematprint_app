<?php

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('site_open')->default(true);
            $table->boolean('discount_enabled')->default(false);
            $table->enum('discount_type', DiscountType::values())->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->enum('discount_scope', DiscountScope::values())->default(DiscountScope::ALL_ORDERS->value);
            $table->enum('shipping_mode', ShippingMode::values())->default(ShippingMode::PAID->value);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->boolean('shipping_free_limit_enabled')->default(false);
            $table->decimal('shipping_free_limit', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
