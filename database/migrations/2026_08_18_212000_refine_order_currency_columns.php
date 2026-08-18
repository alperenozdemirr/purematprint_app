<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'currency')) {
                $table->string('currency', 3)->default('TRY')->after('total');
            }

            if (! Schema::hasColumn('orders', 'foreign_amount')) {
                $table->decimal('foreign_amount', 10, 2)->nullable()->after('currency');
            }

            if (! Schema::hasColumn('orders', 'foreign_currency')) {
                $table->string('foreign_currency', 3)->nullable()->after('foreign_amount');
            }
        });

        if (Schema::hasColumn('orders', 'charge_amount')) {
            DB::table('orders')
                ->whereNotNull('charge_amount')
                ->update([
                    'foreign_amount' => DB::raw('charge_amount'),
                    'foreign_currency' => DB::raw('charge_currency'),
                    'currency' => 'TRY',
                ]);

            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn(['charge_currency', 'charge_amount']);
            });
        }

        Schema::table('payments', function (Blueprint $table): void {
            if (! Schema::hasColumn('payments', 'foreign_currency')) {
                $table->string('foreign_currency', 3)->nullable()->after('paid_amount_foreign');
            }
        });

        if (Schema::hasColumn('payments', 'paid_currency')) {
            DB::table('payments')
                ->where('paid_currency', '!=', 'TRY')
                ->whereNotNull('paid_amount_foreign')
                ->update([
                    'foreign_currency' => DB::raw('paid_currency'),
                    'paid_currency' => 'TRY',
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'charge_amount')) {
                $table->string('charge_currency', 3)->nullable()->after('total');
                $table->decimal('charge_amount', 10, 2)->nullable()->after('charge_currency');
            }
        });

        if (Schema::hasColumn('orders', 'foreign_amount')) {
            DB::table('orders')
                ->whereNotNull('foreign_amount')
                ->update([
                    'charge_amount' => DB::raw('foreign_amount'),
                    'charge_currency' => DB::raw('foreign_currency'),
                ]);
        }

        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'foreign_amount')) {
                $table->dropColumn(['currency', 'foreign_amount', 'foreign_currency']);
            }
        });

        Schema::table('payments', function (Blueprint $table): void {
            if (Schema::hasColumn('payments', 'foreign_currency')) {
                $table->dropColumn('foreign_currency');
            }
        });
    }
};
