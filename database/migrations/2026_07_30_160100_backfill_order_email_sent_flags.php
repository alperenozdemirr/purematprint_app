<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->whereNull('confirmation_email_sent_at')
            ->update(['confirmation_email_sent_at' => DB::raw('created_at')]);

        DB::table('orders')
            ->whereNull('shipped_email_sent_at')
            ->where(function ($query) {
                $query->whereIn('status', ['shipped', 'completed'])
                    ->orWhereNotNull('shipped_at')
                    ->orWhereNotNull('carrier_picked_up_at');
            })
            ->update([
                'shipped_email_sent_at' => DB::raw('COALESCE(shipped_at, carrier_picked_up_at, created_at)'),
                'shipped_email_shipment_id' => DB::raw("COALESCE(shipink_shipment_id, 'legacy')"),
            ]);

        DB::table('orders')
            ->whereNull('delivered_email_sent_at')
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhereNotNull('delivered_at');
            })
            ->update(['delivered_email_sent_at' => DB::raw('COALESCE(delivered_at, created_at)')]);
    }

    public function down(): void
    {
        // No rollback for data backfill.
    }
};
