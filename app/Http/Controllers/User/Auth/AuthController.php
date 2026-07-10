<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthenticateRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => '',
            'password' => $data['password'],
            'type' => UserType::USER,
            'status' => Status::ACTIVE,
            'kvkk_confirm' => true,
            'privacy_confirm' => true,
            'distance_sales_contract_confirm' => true,
        ]);

        return redirect()
            ->route('loginPage')
            ->with('success', 'Hesabınız oluşturuldu. Giriş yapabilirsiniz.');
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
