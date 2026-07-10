<?php

use App\Enums\Status;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->decimal('price',10,2);
            $table->text('description')->nullable();//açıklaması
            $table->text('ingredient')->nullable();//ürün içindekiler
            $table->text('instruction')->nullable();//kullanım talimatı
            $table->integer('stock_count')->default(0);
            $table->foreignId('category_id');
            $table->enum('status',Status::values())->default(Status::ACTIVE);
            $table->boolean('featured_status')->default(0);
            $table->boolean('introduction_status')->default(0); //tanıtım ürünü mü?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
