<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('PureMatPrint — Şifre Sıfırlama')
            ->view('mail.reset-password', [
                'url' => $url,
                'name' => $notifiable->name,
                'expire' => config('auth.passwords.users.expire'),
            ]);
    }
}
