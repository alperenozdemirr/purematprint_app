<?php

declare(strict_types=1);

use App\Enums\OrderDesignType;
use App\Enums\OrderSourceChannel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'design_type')) {
                $table->string('design_type', 40)
                    ->default(OrderDesignType::FROM_SCRATCH->value)
                    ->after('design_status');
            }

            if (! Schema::hasColumn('orders', 'source_channel')) {
                $table->string('source_channel', 40)
                    ->default(OrderSourceChannel::WEBSITE->value)
                    ->after('design_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'source_channel')) {
                $table->dropColumn('source_channel');
            }
            if (Schema::hasColumn('orders', 'design_type')) {
                $table->dropColumn('design_type');
            }
        });
    }
};
