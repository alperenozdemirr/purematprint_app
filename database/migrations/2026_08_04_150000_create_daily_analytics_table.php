<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();

            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('paid_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->unsignedInteger('refunded_orders')->default(0);
            $table->unsignedInteger('completed_orders')->default(0);

            $table->decimal('gross_revenue', 12, 2)->default(0);
            $table->decimal('net_revenue', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('shipping_revenue', 12, 2)->default(0);
            $table->decimal('average_order_value', 12, 2)->default(0);

            $table->unsignedInteger('new_customers')->default(0);
            $table->unsignedInteger('returning_customers')->default(0);
            $table->unsignedInteger('new_registrations')->default(0);

            $table->unsignedInteger('products_sold_quantity')->default(0);
            $table->unsignedInteger('new_products')->default(0);
            $table->unsignedInteger('order_files_uploaded')->default(0);
            $table->unsignedInteger('comments_created')->default(0);

            $table->unsignedInteger('domestic_orders')->default(0);
            $table->unsignedInteger('international_orders')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_analytics');
    }
};
