<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('intro_title')->nullable()->after('logo_id');
            $table->text('intro_description')->nullable()->after('intro_title');
            $table->unsignedBigInteger('intro_image_id')->nullable()->after('intro_description');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'intro_title',
                'intro_description',
                'intro_image_id',
            ]);
        });
    }
};
