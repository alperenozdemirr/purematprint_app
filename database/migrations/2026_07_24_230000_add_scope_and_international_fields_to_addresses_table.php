<?php

declare(strict_types=1);

use App\Enums\AddressScope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('scope')->default(AddressScope::DOMESTIC->value)->after('user_id');
            $table->string('country')->nullable()->after('county_id');
            $table->string('state')->nullable()->after('country');
            $table->string('city_name')->nullable()->after('state');
            $table->string('postal_code', 32)->nullable()->after('city_name');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->change();
            $table->foreignId('county_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['scope', 'country', 'state', 'city_name', 'postal_code']);
            $table->foreignId('city_id')->nullable(false)->change();
            $table->foreignId('county_id')->nullable(false)->change();
        });
    }
};
