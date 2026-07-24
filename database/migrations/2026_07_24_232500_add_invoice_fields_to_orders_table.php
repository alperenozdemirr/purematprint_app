<?php

declare(strict_types=1);

use App\Enums\InvoiceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('invoice_type')->default(InvoiceType::INDIVIDUAL->value)->after('invoice_address_id');
            $table->string('tc_no', 11)->nullable()->after('invoice_type');
            $table->string('company_name')->nullable()->after('tc_no');
            $table->string('tax_number', 20)->nullable()->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['invoice_type', 'tc_no', 'company_name', 'tax_number']);
        });
    }
};
