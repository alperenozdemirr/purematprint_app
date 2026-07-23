<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('email')->nullable()->after('shipping_free_limit');
            $table->string('address')->nullable()->after('email');
            $table->string('mobile_phone')->nullable()->after('address');
            $table->string('business_phone')->nullable()->after('mobile_phone');
            $table->string('instagram_url')->nullable()->after('business_phone');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('facebook_url')->nullable()->after('twitter_url');
            $table->string('whatsapp_phone')->nullable()->after('facebook_url');
            $table->string('short_info')->nullable()->after('whatsapp_phone');
            $table->unsignedBigInteger('logo_id')->nullable()->after('short_info');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'address',
                'mobile_phone',
                'business_phone',
                'instagram_url',
                'twitter_url',
                'facebook_url',
                'whatsapp_phone',
                'short_info',
                'logo_id',
            ]);
        });
    }
};
