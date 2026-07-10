<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('code')->unique();
            $table->decimal('total',10,2);

            $table->decimal('subtotal',10,2)->nullable();
            $table->boolean('is_discount_applied')->default(false);
            $table->integer('discount_slice')->default(0);
            $table->boolean('shipping_is_free')->default(true);
            $table->decimal('shipping_price',10,2)->nullable();

            $table->foreignId('address_id');
            $table->foreignId('invoice_address_id');
            $table->string('note')->nullable();
            $table->enum('status' ,OrderStatus::values())->default(OrderStatus::PREPARING);
            $table->boolean('invoice_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
