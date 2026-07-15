<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('slug')->unique()->after('title');
            $table->text('description')->nullable()->after('slug');
            $table->string('label')->nullable()->after('description');
            $table->foreignId('image_id')->nullable()->after('label');
            $table->unsignedInteger('number')->default(0)->after('image_id');
            $table->string('status')->default('active')->after('number');
        });

        Schema::table('collection_products', function (Blueprint $table) {
            $table->foreignId('collection_id')->after('id');
            $table->foreignId('product_id')->after('collection_id');
            $table->unsignedInteger('number')->default(0)->after('product_id');
            $table->unique(['collection_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_products', function (Blueprint $table) {
            $table->dropUnique(['collection_id', 'product_id']);
            $table->dropColumn(['collection_id', 'product_id', 'number']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['title', 'slug', 'description', 'label', 'image_id', 'number', 'status']);
        });
    }
};
