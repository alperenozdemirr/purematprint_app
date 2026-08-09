<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Http\Services\AdminNotificationService;
use App\Models\Setting;
use Illuminate\View\View;

class AdminLayoutComposer
{
    public function __construct(protected AdminNotificationService $adminNotificationService)
    {
    }

    public function compose(View $view): void
    {
        $setting = Setting::current()->loadMissing('logo');

        $payload = [
            'siteSetting' => $setting,
            'siteLogoUrl' => $setting->logoUrl(),
            'siteLogoIsCustom' => $setting->hasCustomLogo(),
            'adminUnreadNotificationCount' => 0,
            'adminRecentNotifications' => collect(),
        ];

        if (auth('admin')->check() && $view->name() !== 'admin.default.login') {
            $payload['adminUnreadNotificationCount'] = $this->adminNotificationService->unreadCount();
            $payload['adminRecentNotifications'] = $this->adminNotificationService->recent(8);
        }

        $view->with($payload);
    }
}
