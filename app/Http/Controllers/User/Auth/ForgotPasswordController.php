<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if ($this->isActiveUser()) {
            return redirect()->route('index');
        }

        return view('user.default.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->validated('email');

        $user = User::query()
            ->where('email', $email)
            ->where('type', UserType::USER)
            ->where('status', Status::ACTIVE)
            ->first();

        if ($user) {
            try {
                Password::broker('users')->sendResetLink(['email' => $email]);
            } catch (Throwable) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Sıfırlama bağlantısı gönderilemedi. İnternet ve mail ayarlarınızı kontrol edip tekrar deneyin.');
            }
        }

        return back()
            ->withInput($request->only('email'))
            ->with('success', 'Eğer e-posta adresiniz kayıtlıysa şifre sıfırlama bağlantısı gönderildi.');
    }

    private function isActiveUser(): bool
    {
        $user = auth()->user();

        return $user !== null
            && $user->type === UserType::USER
            && $user->status === Status::ACTIVE;
    }
}
