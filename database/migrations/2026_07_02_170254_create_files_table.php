<?php

use App\Enums\ContentType;
use App\Enums\FileType;
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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->integer('user_id')->nullable();
            $table->integer('key_id')->nullable();
            $table->enum('file_type',FileType::values())->default(FileType::IMAGE);
            $table->enum('content_type',ContentType::values())->default(ContentType::OTHER);
            $table->integer('number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
