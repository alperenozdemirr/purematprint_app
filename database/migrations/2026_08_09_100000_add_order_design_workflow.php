<?php

declare(strict_types=1);

use App\Enums\ContentType;
use App\Enums\OrderDesignStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'design_status')) {
                $table->string('design_status', 40)
                    ->default(OrderDesignStatus::NONE->value)
                    ->after('status');
            }
        });

        if (! Schema::hasTable('order_design_requests')) {
            Schema::create('order_design_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('file_id')->nullable()->constrained('files')->nullOnDelete();
                $table->string('type', 40);
                $table->string('actor_type', 20);
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'created_at']);
            });
        }

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            $values = implode("','", ContentType::values());
            DB::statement(
                "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_design_requests');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'design_status')) {
                $table->dropColumn('design_status');
            }
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            $previous = [
                ContentType::PRODUCT->value,
                ContentType::USER->value,
                ContentType::OTHER->value,
                ContentType::BANNER->value,
                ContentType::COLLECTION->value,
                ContentType::BLOG->value,
                ContentType::COMMENT->value,
                ContentType::ORDER_FILE->value,
                ContentType::ORDER_INVOICE->value,
            ];
            $values = implode("','", $previous);
            DB::statement(
                "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
            );
        }
    }
};
