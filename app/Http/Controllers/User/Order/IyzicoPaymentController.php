<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Order\Concerns\RestoresPaymentSession;
use App\Http\Services\CheckoutCompletionService;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\IyzicoPaymentService;
use App\Jobs\UploadOrderFilesJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IyzicoPaymentController extends Controller
{
    use RestoresPaymentSession;

    public function __construct(
        protected IyzicoPaymentService $iyzicoService,
        protected CheckoutDraftService $draftService,
        protected CheckoutCompletionService $completionService,
    ) {
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = (string) $request->input('token', '');
        $provider = PaymentProvider::IYZICO;

        if ($token === '') {
            return $this->redirectToCheckout('Ödeme doğrulaması yapılamadı. Lütfen tekrar deneyin.');
        }

        $existingPayment = Payment::query()
            ->with(['order.user'])
            ->where('provider_token', $token)
            ->where('status', PaymentStatus::COMPLETED)
            ->first();

        if ($existingPayment !== null) {
            $this->restoreAuthenticatedUser($request, $existingPayment->order->user);

            return $this->redirectToOrder($existingPayment->order, 'success', 'Ödemeniz zaten alınmış.');
        }

        $completedCode = $this->draftService->completedOrderCode($provider, $token);

        if ($completedCode !== null) {
            $order = Order::query()->with('user')->where('code', $completedCode)->first();

            if ($order !== null) {
                $this->restoreAuthenticatedUser($request, $order->user);

                return $this->redirectToOrder($order, 'success', 'Ödemeniz zaten alınmış.');
            }
        }

        $draft = $this->draftService->get($provider, $token);

        if ($draft === null) {
            return $this->redirectToCheckout('Ödeme oturumu bulunamadı veya süresi doldu. Lütfen tekrar deneyin.');
        }

        $user = User::query()->find($draft['user_id']);

        if ($user === null) {
            $this->draftService->forget($provider, $token);

            return $this->redirectToCheckout('Kullanıcı hesabı bulunamadı.');
        }

        if (! $this->iyzicoService->isConfigured()) {
            $this->draftService->forget($provider, $token);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout('Ödeme sistemi yapılandırılmamış.');
        }

        try {
            $checkoutForm = $this->iyzicoService->retrieveCheckout($token);
        } catch (\Throwable $exception) {
            report($exception);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout('Ödeme sonucu alınamadı. Lütfen tekrar deneyin.');
        }

        if (! $this->iyzicoService->isPaymentSuccessful($checkoutForm)) {
            $this->draftService->forget($provider, $token);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout(
                $checkoutForm->getErrorMessage() ?: 'Ödeme tamamlanamadı. Sepetiniz korunmuştur, tekrar deneyebilirsiniz.'
            );
        }

        $stockError = $this->completionService->validateDraftStock($draft);

        if ($stockError !== null) {
            $this->draftService->forget($provider, $token);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout($stockError);
        }

        $order = $this->completionService->completeFromDraft(
            $draft,
            $provider,
            $token,
            $checkoutForm->getPaymentId(),
            (float) ($checkoutForm->getPaidPrice() ?? $draft['summary']['total']),
        );

        $pendingFiles = is_array($draft['files'] ?? null) ? $draft['files'] : [];

        if ($pendingFiles !== []) {
            UploadOrderFilesJob::dispatch($order->id, $pendingFiles);
        }

        $this->draftService->markCompleted($provider, $token, $order->code);
        $this->completionService->sendConfirmationEmail($order);
        $this->restoreAuthenticatedUser($request, $order->user);

        return $this->redirectToOrder($order, 'success', 'Ödemeniz alındı. Siparişiniz oluşturuldu.');
    }
}
