<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['sub_title', 'title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('sub_title')->after('id');
            $table->string('title')->after('sub_title');
            $table->string('description')->after('image_id');
        });
    }
};
