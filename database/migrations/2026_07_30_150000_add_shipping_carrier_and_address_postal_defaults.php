<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_carrier', 32)->nullable()->after('shipink_shipment_id');
            $table->timestamp('shipment_created_at')->nullable()->after('shipping_carrier');
        });

        DB::table('addresses')
            ->where('scope', 'domestic')
            ->where(function ($query) {
                $query->whereNull('postal_code')->orWhere('postal_code', '');
            })
            ->update(['postal_code' => '54000']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_carrier', 'shipment_created_at']);
        });
    }
};
