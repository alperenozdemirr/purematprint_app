<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            $orderStatuses = implode("','", OrderStatus::values());
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('{$orderStatuses}') NOT NULL DEFAULT '".OrderStatus::PREPARING->value."'");

            $paymentStatuses = implode("','", PaymentStatus::values());
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('{$paymentStatuses}') NOT NULL DEFAULT '".PaymentStatus::PENDING->value."'");
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider', 32)->nullable()->after('status');
            $table->string('provider_payment_id')->nullable()->after('provider');
            $table->string('provider_token')->nullable()->after('provider_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_payment_id', 'provider_token']);
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('preparing','shipped','completed','cancelled') NOT NULL DEFAULT 'preparing'");
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('completed','refunded') NOT NULL DEFAULT 'completed'");
        }
    }
};
