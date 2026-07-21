<?php

use App\Enums\ContentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $values = implode("','", ContentType::values());

        DB::statement(
            "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
        );
    }

    public function down(): void
    {
        $values = implode("','", array_filter(
            ContentType::values(),
            fn (string $value): bool => $value !== ContentType::BLOG->value
        ));

        DB::statement(
            "ALTER TABLE files MODIFY COLUMN content_type ENUM('{$values}') NOT NULL DEFAULT '".ContentType::OTHER->value."'"
        );
    }
};
