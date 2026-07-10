<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthenticateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginPage(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->type === UserType::ADMIN && $user->status === Status::ACTIVE) {
            return redirect()->route('admin.index');
        }

        return view('admin.default.login');
    }

    public function authenticate(AuthenticateRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'type' => UserType::ADMIN->value,
            'status' => Status::ACTIVE->value,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.index'))
                ->with('success', 'Başarıyla giriş yaptınız.');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'E-posta veya şifre hatalı.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.loginPage')
            ->with('success', 'Çıkış yapıldı.');
    }
}
