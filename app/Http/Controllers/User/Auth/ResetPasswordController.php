<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function create(Request $request, ?string $token = null): View
    {
        return view('user.default.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        return $this->reset($request);
    }

    protected function redirectTo(): string
    {
        return route('loginPage');
    }

    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    protected function resetPassword($user, $password): void
    {
        if ($user->type !== UserType::USER || $user->status !== Status::ACTIVE) {
            abort(403);
        }

        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    }

    protected function sendResetResponse(Request $request, $response): RedirectResponse
    {
        return redirect($this->redirectPath())
            ->with('success', 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.');
    }

    protected function sendResetFailedResponse(Request $request, $response): RedirectResponse
    {
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Şifre sıfırlama bağlantısı geçersiz veya süresi dolmuş.');
    }
}
