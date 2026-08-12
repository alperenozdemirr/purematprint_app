<?php

namespace App\Providers;

use App\Support\MailUrl;
use App\View\Composers\AdminLayoutComposer;
use App\View\Composers\MailViewComposer;
use App\View\Composers\UserLayoutComposer;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('user.*', UserLayoutComposer::class);
        View::composer(['admin.layout', 'admin.default.login'], AdminLayoutComposer::class);
        View::composer(['mail.*', 'mail.partials.*'], MailViewComposer::class);

        Event::listen(JobProcessing::class, function (JobProcessing $event): void {
            $jobName = $event->job->resolveName();

            if (in_array($jobName, [SendQueuedMailable::class, SendQueuedNotifications::class], true)) {
                MailUrl::apply();
            }
        });

        Event::listen(NotificationSending::class, function (NotificationSending $event): void {
            if ($event->channel === 'mail') {
                MailUrl::apply();
            }
        });
    }
}
