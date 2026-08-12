<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('spotlight_title')->nullable()->after('logo_id');
            $table->string('spotlight_subtitle', 120)->nullable()->after('spotlight_title');
            $table->unsignedBigInteger('spotlight_image_id')->nullable()->after('spotlight_subtitle');
            $table->unsignedBigInteger('band_image_id')->nullable()->after('spotlight_image_id');
            $table->string('team_note_title')->nullable()->after('band_image_id');
            $table->text('team_note_description')->nullable()->after('team_note_title');
            $table->unsignedBigInteger('team_note_image_id')->nullable()->after('team_note_description');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'spotlight_title',
                'spotlight_subtitle',
                'spotlight_image_id',
                'band_image_id',
                'team_note_title',
                'team_note_description',
                'team_note_image_id',
            ]);
        });
    }
};
