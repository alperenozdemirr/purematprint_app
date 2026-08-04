<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Services\OrderEmailService;
use App\Jobs\SendNewOrderAdminNotificationJob;
use App\Mail\NewOrderAdminNotificationMail;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class NewOrderAdminNotificationTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_setting_normalizes_notification_emails(): void
    {
        Setting::saveSingleton([
            'order_notification_emails' => [
                ' Admin@Example.com ',
                'admin@example.com',
                'invalid',
                'ops@example.com',
                'fourth@example.com',
                'fifth@example.com',
            ],
        ]);

        $emails = Setting::current()->orderNotificationEmails();

        $this->assertSame([
            'admin@example.com',
            'ops@example.com',
            'fourth@example.com',
            'fifth@example.com',
        ], $emails);
    }

    public function test_admin_notification_job_dispatched_after_order(): void
    {
        Bus::fake();

        Setting::saveSingleton([
            'order_notification_emails' => ['shop@example.com'],
        ]);

        $order = $this->createDomesticOrder();

        app(OrderEmailService::class)->sendAdminNewOrderNotificationIfNeeded($order);

        Bus::assertDispatched(SendNewOrderAdminNotificationJob::class, function (SendNewOrderAdminNotificationJob $job) use ($order) {
            return $job->orderId === $order->id;
        });
    }

    public function test_admin_notification_not_dispatched_when_no_recipients(): void
    {
        Bus::fake();

        Setting::saveSingleton([
            'order_notification_emails' => null,
        ]);

        $order = $this->createDomesticOrder();

        $sent = app(OrderEmailService::class)->sendAdminNewOrderNotificationIfNeeded($order);

        $this->assertFalse($sent);
        Bus::assertNotDispatched(SendNewOrderAdminNotificationJob::class);
    }

    public function test_admin_notification_job_sends_once_when_run_concurrently(): void
    {
        Mail::fake();

        Setting::saveSingleton([
            'order_notification_emails' => ['a@example.com', 'b@example.com', 'c@example.com'],
        ]);

        $order = $this->createDomesticOrder();

        $job = new SendNewOrderAdminNotificationJob($order->id);
        $job->handle();
        $job->handle();

        Mail::assertSent(NewOrderAdminNotificationMail::class, 3);
    }

    public function test_admin_notification_dispatch_is_blocked_for_duplicate_requests(): void
    {
        Bus::fake();

        Setting::saveSingleton([
            'order_notification_emails' => ['shop@example.com'],
        ]);

        $order = $this->createDomesticOrder();
        $service = app(OrderEmailService::class);

        $this->assertTrue($service->sendAdminNewOrderNotificationIfNeeded($order));
        $this->assertFalse($service->sendAdminNewOrderNotificationIfNeeded($order->fresh()));

        Bus::assertDispatched(SendNewOrderAdminNotificationJob::class, 1);
    }
}
