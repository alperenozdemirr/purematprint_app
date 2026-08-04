<?php

use App\Enums\ContentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        $values = implode("','", array_filter(
            ContentType::values(),
            fn (string $value): bool => $value !== ContentType::BLOG->value
        ));

        DB::statement(
            "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
        );
    }
};
