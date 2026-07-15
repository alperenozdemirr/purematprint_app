<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthenticateRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Mail\WelcomeUserMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function loginPage(): View|RedirectResponse
    {
        if ($this->isActiveUser()) {
            return redirect()->route('index');
        }

        return view('user.default.login');
    }

    public function registerPage(): View|RedirectResponse
    {
        if ($this->isActiveUser()) {
            return redirect()->route('index');
        }

        return view('user.default.register');
    }

    public function authenticate(AuthenticateRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'type' => UserType::USER->value,
            'status' => Status::ACTIVE->value,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('index'))
                ->with('success', 'Başarıyla giriş yaptınız.');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'E-posta veya şifre hatalı.');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $request->session()->put([
            'register.name' => $data['name'],
            'register.email' => $data['email'],
            'register.password' => Crypt::encryptString($data['password']),
        ]);

        return redirect()
            ->route('registerVerifyPage')
            ->with('success', 'Doğrulama kodu e-posta adresinize gönderilecek.');
    }

    public function registerSave(): bool
    {
        if (! session()->has(['register.name', 'register.email', 'register.password'])) {
            return false;
        }

        try {
            $email = (string) session('register.email');

            if (User::query()->where('email', $email)->exists()) {
                return false;
            }

            $name = (string) session('register.name');

            User::create([
                'name' => $name,
                'email' => $email,
                'phone' => '',
                'password' => Crypt::decryptString((string) session('register.password')),
                'type' => UserType::USER,
                'status' => Status::ACTIVE,
                'kvkk_confirm' => true,
                'privacy_confirm' => true,
                'distance_sales_contract_confirm' => true,
                'email_verified_at' => now(),
            ]);

            try {
                Mail::to($email)->send(new WelcomeUserMail($name));
            } catch (Throwable) {
                // Kayıt tamamlandı; hoş geldiniz maili gönderilemese de akış devam eder.
            }

            EmailVerification::query()->where('email', $email)->delete();

            session()->forget([
                'register.name',
                'register.email',
                'register.password',
                'register.code_sent_at',
            ]);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('index')
            ->with('success', 'Çıkış yapıldı.');
    }

    private function isActiveUser(): bool
    {
        $user = Auth::user();

        return $user
            && $user->type === UserType::USER
            && $user->status === Status::ACTIVE;
    }
}
