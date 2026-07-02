<?php

use App\Enums\Status;
use App\Enums\UserType;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('type',UserType::values())->default(UserType::USER);
            $table->enum('status',Status::values())->default(Status::ACTIVE);
            $table->boolean('kvkk_confirm')->default(true);
            $table->boolean('privacy_confirm')->default(true);
            $table->boolean('distance_sales_contract_confirm')->default(true);
            $table->foreignId('image_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
