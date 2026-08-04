<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('order_notification_emails')->nullable()->after('email');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('admin_notification_sent_at')->nullable()->after('confirmation_email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('order_notification_emails');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('admin_notification_sent_at');
        });
    }
};
