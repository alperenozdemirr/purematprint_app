<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Support\MailUrl;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $url = MailUrl::route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('PureMatPrint — Şifre Sıfırlama')
            ->view('mail.reset-password', [
                'url' => $url,
                'name' => $notifiable->name,
                'expire' => config('auth.passwords.users.expire'),
            ]);
    }
}
