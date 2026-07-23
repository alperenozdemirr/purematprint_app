<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class AdminLayoutComposer
{
    public function compose(View $view): void
    {
        $setting = Setting::current()->loadMissing('logo');

        $view->with([
            'siteSetting' => $setting,
            'siteLogoUrl' => $setting->logoUrl(),
            'siteLogoIsCustom' => $setting->hasCustomLogo(),
        ]);
    }
}
