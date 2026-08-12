<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Support\MailBranding;
use App\Support\MailUrl;
use Illuminate\View\View;

class MailViewComposer
{
    public function compose(View $view): void
    {
        MailUrl::apply();

        $view->with([
            'mailAnnounce' => MailBranding::COLOR_ANNOUNCE,
            'mailBg' => MailBranding::COLOR_BG,
            'mailSurface' => MailBranding::COLOR_SURFACE,
            'mailCream' => MailBranding::COLOR_CREAM,
            'mailInk' => MailBranding::COLOR_INK,
            'mailMuted' => MailBranding::COLOR_MUTED,
            'mailAction' => MailBranding::COLOR_ACTION,
            'mailOnDark' => MailBranding::COLOR_ON_DARK,
            'mailDark' => MailBranding::COLOR_DARK,
            'mailFontBody' => MailBranding::FONT_BODY,
            'mailFontHeading' => MailBranding::FONT_HEADING,
            'mailLogoUrl' => MailBranding::logoUrl(),
            'mailSiteUrl' => MailUrl::root(),
        ]);
    }
}
