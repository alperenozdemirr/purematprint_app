<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('shipping_duration_text', 120)->nullable()->after('shipping_free_limit');
            $table->string('delivery_time_text', 120)->nullable()->after('shipping_duration_text');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['shipping_duration_text', 'delivery_time_text']);
        });
    }
};
