<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Order\Concerns\RestoresPaymentSession;
use App\Http\Services\CheckoutCompletionService;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\StripePaymentService;
use App\Jobs\UploadOrderFilesJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    use RestoresPaymentSession;

    public function __construct(
        protected StripePaymentService $stripeService,
        protected CheckoutDraftService $draftService,
        protected CheckoutCompletionService $completionService,
    ) {
    }

    public function success(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');
        $provider = PaymentProvider::STRIPE;

        if ($sessionId === '') {
            return $this->redirectToCheckout('Ödeme doğrulaması yapılamadı. Lütfen tekrar deneyin.');
        }

        $existingPayment = Payment::query()
            ->with(['order.user'])
            ->where('provider_token', $sessionId)
            ->where('status', PaymentStatus::COMPLETED)
            ->first();

        if ($existingPayment !== null) {
            $this->restoreAuthenticatedUser($request, $existingPayment->order->user);

            return $this->redirectToOrder($existingPayment->order, 'success', 'Ödemeniz zaten alınmış.');
        }

        $completedCode = $this->draftService->completedOrderCode($provider, $sessionId);

        if ($completedCode !== null) {
            $order = Order::query()->with('user')->where('code', $completedCode)->first();

            if ($order !== null) {
                $this->restoreAuthenticatedUser($request, $order->user);

                return $this->redirectToOrder($order, 'success', 'Ödemeniz zaten alınmış.');
            }
        }

        $draft = $this->draftService->get($provider, $sessionId);

        if ($draft === null) {
            return $this->redirectToCheckout('Ödeme oturumu bulunamadı veya süresi doldu. Lütfen tekrar deneyin.');
        }

        $user = User::query()->find($draft['user_id']);

        if ($user === null) {
            $this->draftService->forget($provider, $sessionId);

            return $this->redirectToCheckout('Kullanıcı hesabı bulunamadı.');
        }

        if (! $this->stripeService->isConfigured()) {
            $this->draftService->forget($provider, $sessionId);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout('Stripe ödeme sistemi yapılandırılmamış.');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
        } catch (\Throwable $exception) {
            report($exception);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout('Ödeme sonucu alınamadı. Lütfen tekrar deneyin.');
        }

        if (! $this->stripeService->isPaymentSuccessful($session)) {
            $this->draftService->forget($provider, $sessionId);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout('Ödeme tamamlanamadı. Sepetiniz korunmuştur, tekrar deneyebilirsiniz.');
        }

        $stockError = $this->completionService->validateDraftStock($draft);

        if ($stockError !== null) {
            $this->draftService->forget($provider, $sessionId);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout($stockError);
        }

        $totalTry = (float) $draft['summary']['total'];
        $foreignAmount = $this->stripeService->paidAmount($session);

        if ($foreignAmount <= 0) {
            $foreignAmount = (float) ($draft['summary']['chargeTotal'] ?? 0);
        }

        $fxRate = app(\App\Http\Services\CurrencyConversionService::class)
            ->impliedEurPerTry($totalTry, $foreignAmount)
            ?? (isset($draft['summary']['fxRate']) ? (float) $draft['summary']['fxRate'] : null);

        $order = $this->completionService->completeFromDraft(
            $draft,
            $provider,
            $sessionId,
            $this->stripeService->paymentReference($session),
            $totalTry,
            $this->stripeService->paidCurrency($session),
            $foreignAmount > 0 ? $foreignAmount : null,
            $fxRate,
        );

        $pendingFiles = is_array($draft['files'] ?? null) ? $draft['files'] : [];

        if ($pendingFiles !== []) {
            UploadOrderFilesJob::dispatch($order->id, $pendingFiles);
        }

        $this->draftService->markCompleted($provider, $sessionId, $order->code);
        $this->completionService->sendConfirmationEmail($order);
        $this->restoreAuthenticatedUser($request, $order->user);

        return $this->redirectToOrder($order, 'success', 'Ödemeniz alındı. Siparişiniz oluşturuldu.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');
        $provider = PaymentProvider::STRIPE;

        if ($sessionId !== '') {
            $draft = $this->draftService->get($provider, $sessionId);
            $this->draftService->forget($provider, $sessionId);

            if ($draft !== null) {
                $user = User::query()->find($draft['user_id']);
                $this->restoreAuthenticatedUser($request, $user);
            }
        }

        return $this->redirectToCheckout('Ödeme iptal edildi. Sepetiniz korunmuştur.');
    }
}
