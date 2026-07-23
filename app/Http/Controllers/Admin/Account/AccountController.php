<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountUpdateRequest;
use App\Http\Requests\Admin\PasswordUpdateRequest;
use App\Mail\PasswordChangedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('admin.account', [
            'user' => auth()->user(),
        ]);
    }

    public function update(AccountUpdateRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return back()->with('success', 'Hesap bilgileriniz güncellendi.');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => $request->validated('password'),
        ]);

        $this->sendPasswordChangedMail($user);

        return back()->with('success', 'Şifreniz güncellendi. Güvenlik bildirimi e-posta adresinize gönderildi.');
    }

    protected function sendPasswordChangedMail($user): void
    {
        try {
            Mail::to($user->email)->send(new PasswordChangedMail(
                $user,
                now()->timezone(config('app.timezone'))->format('d.m.Y H:i'),
                'admin',
            ));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
