<?php

use App\Enums\ContentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('file_name');
        });

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        $values = implode("','", ContentType::values());

        DB::statement(
            "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
        );
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        $values = implode("','", array_filter(
            ContentType::values(),
            fn (string $value): bool => $value !== ContentType::ORDER_FILE->value
        ));

        DB::statement(
            "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
        );
    }
};
