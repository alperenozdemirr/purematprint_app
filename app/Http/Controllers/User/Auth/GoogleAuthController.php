<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if ($this->isActiveUser()) {
            return redirect()->route('index');
        }

        $intent = $request->query('intent') === 'register' ? 'register' : 'login';
        session(['google.intent' => $intent]);

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if ($this->isActiveUser()) {
            return redirect()->route('index');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('loginPage')
                ->with('error', 'Google ile giriş başarısız oldu. Redirect URI ve OAuth ayarlarınızı kontrol edin.');
        }

        $email = $googleUser->getEmail();
        if (! $email) {
            return redirect()->route('loginPage')
                ->with('error', 'Google hesabınızdan e-posta bilgisi alınamadı.');
        }

        session()->pull('google.intent', 'login');

        $user = User::query()->where('google_id', $googleUser->getId())->first();
        $isNewUser = false;

        if ($user) {
            if (! $this->canLoginAsUser($user)) {
                return redirect()->route('loginPage')
                    ->with('error', 'Bu hesapla giriş yapılamaz.');
            }
        } else {
            $existingUser = User::query()->where('email', $email)->first();

            if ($existingUser) {
                if (! $this->canLoginAsUser($existingUser)) {
                    return redirect()->route('loginPage')
                        ->with('error', 'Bu e-posta admin hesabına ait. Lütfen admin girişini kullanın.');
                }

                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $existingUser->email_verified_at ?? now(),
                ]);

                $user = $existingUser;
            } else {
                $isNewUser = true;

                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Google Kullanıcı',
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'phone' => '',
                    'password' => Str::random(32),
                    'type' => UserType::USER,
                    'status' => Status::ACTIVE,
                    'kvkk_confirm' => true,
                    'privacy_confirm' => true,
                    'distance_sales_contract_confirm' => true,
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($isNewUser) {
            try {
                Mail::to($user->email)->send(new WelcomeUserMail($user->name));
            } catch (Throwable) {
                // Kayıt tamamlandı; hoş geldiniz maili gönderilemese de akış devam eder.
            }

            return redirect()->intended(route('index'))
                ->with('success', 'Google hesabınızla kayıt oldunuz. Hoş geldiniz!');
        }

        return redirect()->intended(route('index'))
            ->with('success', 'Google ile başarıyla giriş yaptınız.');
    }

    private function isActiveUser(): bool
    {
        $user = Auth::user();

        return $user !== null
            && $user->type === UserType::USER
            && $user->status === Status::ACTIVE;
    }

    private function canLoginAsUser(User $user): bool
    {
        return $user->type === UserType::USER
            && $user->status === Status::ACTIVE;
    }
}
