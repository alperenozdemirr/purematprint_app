<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\EmailVerification;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Requests\User\RegisterVerifyRequest;
use App\Mail\VerifyEmailMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class EmailVerificationController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! session()->has('register.email')) {
            return redirect()->route('registerPage');
        }

        $email = (string) session('register.email');

        if (User::query()->where('email', $email)->exists()) {
            $this->clearRegisterSession();

            return redirect()
                ->route('registerPage')
                ->with('error', 'Bu e-posta adresi zaten kayıtlı.');
        }

        return view('user.default.register-verify', [
            'email' => $email,
            'maskedEmail' => $this->maskEmail($email),
        ]);
    }

    public function sendCode(): JsonResponse
    {
        if (! session()->has('register.email')) {
            return response()->json([
                'status' => false,
                'message' => 'Kayıt oturumu bulunamadı. Lütfen tekrar kayıt olun.',
            ], 404);
        }

        $email = (string) session('register.email');

        if (User::query()->where('email', $email)->exists()) {
            $this->clearRegisterSession();

            return response()->json([
                'status' => false,
                'message' => 'Bu e-posta adresi zaten kayıtlı.',
            ], 422);
        }

        $lastSentAt = session('register.code_sent_at');
        if ($lastSentAt && Carbon::parse($lastSentAt)->diffInSeconds(now()) < 60) {
            $remaining = 60 - Carbon::parse($lastSentAt)->diffInSeconds(now());

            return response()->json([
                'status' => false,
                'message' => 'Yeni kod göndermek için '.$remaining.' saniye bekleyin.',
            ], 429);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerification::query()->updateOrCreate(
            ['email' => $email],
            [
                'verification_code' => Hash::make($code),
                'confirm_status' => false,
            ]
        );

        try {
            Mail::to($email)->send(new VerifyEmailMail($code));
        } catch (Throwable) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama kodu gönderilemedi. Lütfen daha sonra tekrar deneyin.',
            ], 422);
        }

        session(['register.code_sent_at' => now()->toDateTimeString()]);

        return response()->json([
            'status' => true,
            'message' => 'Doğrulama kodu e-posta adresinize gönderildi.',
        ]);
    }

    public function verify(RegisterVerifyRequest $request, AuthController $authController): JsonResponse
    {
        if (! session()->has(['register.name', 'register.email', 'register.password'])) {
            return response()->json([
                'status' => false,
                'message' => 'Kayıt oturumu bulunamadı. Lütfen tekrar kayıt olun.',
            ], 404);
        }

        $email = (string) session('register.email');
        $verification = EmailVerification::query()->where('email', $email)->first();

        if (! $verification) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama kaydı bulunamadı. Yeni kod isteyin.',
            ], 404);
        }

        if (Carbon::parse($verification->created_at)->diffInMinutes(now()) > 10) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama kodunun süresi doldu. Yeni kod isteyin.',
            ], 422);
        }

        if (! Hash::check($request->validated('code'), $verification->verification_code)) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama kodu hatalı.',
            ], 422);
        }

        $verification->update(['confirm_status' => true]);

        if (! $authController->registerSave()) {
            return response()->json([
                'status' => false,
                'message' => 'Hesap oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.',
            ], 500);
        }

        session()->forget('register.code_sent_at');

        return response()->json([
            'status' => true,
            'message' => 'E-posta doğrulandı. Giriş sayfasına yönlendiriliyorsunuz.',
            'redirect' => route('loginPage'),
        ]);
    }

    private function clearRegisterSession(): void
    {
        session()->forget([
            'register.name',
            'register.email',
            'register.password',
            'register.code_sent_at',
        ]);
    }

    private function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));
        $maskedLocal = $visible.str_repeat('*', max(1, mb_strlen($local) - mb_strlen($visible)));

        return $maskedLocal.'@'.$domain;
    }
}
