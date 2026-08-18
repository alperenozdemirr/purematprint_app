<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('charge_currency', 3)->nullable()->after('total');
            $table->decimal('charge_amount', 10, 2)->nullable()->after('charge_currency');
            $table->decimal('fx_rate', 12, 6)->nullable()->after('charge_amount');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('paid_currency', 3)->default('TRY')->after('paid_amount');
            $table->decimal('paid_amount_foreign', 10, 2)->nullable()->after('paid_currency');
            $table->decimal('fx_rate', 12, 6)->nullable()->after('paid_amount_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['charge_currency', 'charge_amount', 'fx_rate']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paid_currency', 'paid_amount_foreign', 'fx_rate']);
        });
    }
};
