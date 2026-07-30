<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('shipink_warehouse_id')->nullable()->after('logo_id');
            $table->string('shipink_warehouse_name')->nullable()->after('shipink_warehouse_id');
            $table->string('shipink_carrier_account_id')->nullable()->after('shipink_warehouse_name');
            $table->string('shipink_carrier_account_label')->nullable()->after('shipink_carrier_account_id');
            $table->string('shipink_carrier_service_id')->nullable()->after('shipink_carrier_account_label');
            $table->string('shipink_card_id')->nullable()->after('shipink_carrier_service_id');
            $table->string('shipink_card_label')->nullable()->after('shipink_card_id');
            $table->unsignedSmallInteger('shipink_default_weight')->default(1)->after('shipink_card_label');
            $table->unsignedSmallInteger('shipink_default_length')->default(20)->after('shipink_default_weight');
            $table->unsignedSmallInteger('shipink_default_width')->default(15)->after('shipink_default_length');
            $table->unsignedSmallInteger('shipink_default_height')->default(10)->after('shipink_default_width');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'shipink_warehouse_id',
                'shipink_warehouse_name',
                'shipink_carrier_account_id',
                'shipink_carrier_account_label',
                'shipink_carrier_service_id',
                'shipink_card_id',
                'shipink_card_label',
                'shipink_default_weight',
                'shipink_default_length',
                'shipink_default_width',
                'shipink_default_height',
            ]);
        });
    }
};
