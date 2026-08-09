<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_property_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 20);
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_property_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('product_property_groups')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_id', 'sort_order']);
        });

        Schema::create('order_detail_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->constrained('order_details')->cascadeOnDelete();
            $table->string('group_title');
            $table->string('property_title');
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('property_item_id')->nullable()->constrained('product_property_items')->nullOnDelete();
            $table->timestamps();

            $table->index('order_detail_id');
        });

        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->json('selected_property_item_ids')->nullable()->after('quantity');
            $table->string('property_signature', 64)->default('')->after('selected_property_item_ids');
        });

        // Aynı kullanıcı + ürün için mükerrer sepet satırlarını temizle (unique index öncesi).
        $duplicates = \Illuminate\Support\Facades\DB::table('shopping_carts')
            ->select('user_id', 'product_id')
            ->groupBy('user_id', 'product_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $ids = \Illuminate\Support\Facades\DB::table('shopping_carts')
                ->where('user_id', $dup->user_id)
                ->where('product_id', $dup->product_id)
                ->orderByDesc('id')
                ->pluck('id');

            $keep = $ids->shift();
            if ($ids->isNotEmpty()) {
                \Illuminate\Support\Facades\DB::table('shopping_carts')->whereIn('id', $ids)->delete();
            }
            unset($keep);
        }

        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'property_signature'], 'shopping_carts_user_product_signature_unique');
        });
    }

    public function down(): void
    {
        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->dropUnique('shopping_carts_user_product_signature_unique');
            $table->dropColumn(['selected_property_item_ids', 'property_signature']);
        });

        Schema::dropIfExists('order_detail_properties');
        Schema::dropIfExists('product_property_items');
        Schema::dropIfExists('product_property_groups');
    }
};
