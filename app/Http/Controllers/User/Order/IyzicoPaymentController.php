<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\IyzicoPaymentService;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IyzicoPaymentController extends Controller
{
    public function __construct(
        protected IyzicoPaymentService $iyzicoService,
        protected CheckoutDraftService $draftService,
    ) {
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = (string) $request->input('token', '');

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

        $completedCode = $this->draftService->completedOrderCode($token);

        if ($completedCode !== null) {
            $order = Order::query()->with('user')->where('code', $completedCode)->first();

            if ($order !== null) {
                $this->restoreAuthenticatedUser($request, $order->user);

                return $this->redirectToOrder($order, 'success', 'Ödemeniz zaten alınmış.');
            }
        }

        $draft = $this->draftService->get($token);

        if ($draft === null) {
            return $this->redirectToCheckout('Ödeme oturumu bulunamadı veya süresi doldu. Lütfen tekrar deneyin.');
        }

        $user = User::query()->find($draft['user_id']);

        if ($user === null) {
            $this->draftService->forget($token);

            return $this->redirectToCheckout('Kullanıcı hesabı bulunamadı.');
        }

        if (! $this->iyzicoService->isConfigured()) {
            $this->draftService->forget($token);
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
            $this->draftService->forget($token);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout(
                $checkoutForm->getErrorMessage() ?: 'Ödeme tamamlanamadı. Sepetiniz korunmuştur, tekrar deneyebilirsiniz.'
            );
        }

        $stockError = $this->validateDraftStock($draft);

        if ($stockError !== null) {
            $this->draftService->forget($token);
            $this->restoreAuthenticatedUser($request, $user);

            return $this->redirectToCheckout($stockError);
        }

        $order = DB::transaction(function () use ($draft, $checkoutForm, $token) {
            $summary = $draft['summary'];
            $invoice = $draft['invoice'];

            $order = Order::create(array_merge([
                'user_id' => $draft['user_id'],
                'code' => Order::generateCode(),
                'subtotal' => $summary['subtotal'],
                'is_discount_applied' => $summary['discountApplied'],
                'discount_type' => $summary['discountType'],
                'discount_slice' => (int) round($summary['discountValue'] ?? 0),
                'discount_amount' => $summary['discountAmount'],
                'shipping_is_free' => $summary['shippingFree'],
                'shipping_price' => $summary['shippingCost'],
                'total' => $summary['total'],
                'address_id' => $draft['address_id'],
                'invoice_address_id' => $draft['address_id'],
                'note' => $draft['note'] ?? null,
                'status' => OrderStatus::PREPARING,
                'invoice_status' => false,
            ], $invoice));

            foreach ($draft['items'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Product::query()
                    ->where('id', $item['product_id'])
                    ->decrement('stock_count', $item['quantity']);
            }

            Payment::create([
                'user_id' => $draft['user_id'],
                'order_id' => $order->id,
                'paid_amount' => $checkoutForm->getPaidPrice() ?? $summary['total'],
                'status' => PaymentStatus::COMPLETED,
                'provider' => PaymentProvider::IYZICO,
                'provider_payment_id' => $checkoutForm->getPaymentId(),
                'provider_token' => $token,
            ]);

            ShoppingCart::query()->where('user_id', $draft['user_id'])->delete();

            return $order;
        });

        $this->draftService->markCompleted($token, $order->code);

        $order->load([
            'user',
            'details.product',
            'address.city',
            'address.county',
        ]);

        try {
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
        } catch (\Throwable $exception) {
            report($exception);
        }

        $this->restoreAuthenticatedUser($request, $order->user);

        return $this->redirectToOrder($order, 'success', 'Ödemeniz alındı. Siparişiniz oluşturuldu.');
    }

    /**
     * iyzico cross-site POST callback does not send the session cookie (SameSite),
     * which creates a fresh empty session and logs the user out. Re-authenticate
     * on the callback session before redirecting back into the app.
     */
    private function restoreAuthenticatedUser(Request $request, ?User $user): void
    {
        if ($user === null) {
            return;
        }

        Auth::login($user);
        $request->session()->regenerate();
    }

    private function validateDraftStock(array $draft): ?string
    {
        foreach ($draft['items'] as $item) {
            $product = Product::query()->find($item['product_id']);

            if ($product === null || $product->status !== Status::ACTIVE) {
                return 'Sepetinizdeki bir ürün artık satışta değil.';
            }

            if ($product->stock_count < $item['quantity']) {
                return $product->title.' için yeterli stok kalmadı.';
            }
        }

        return null;
    }

    private function redirectToCheckout(string $message): RedirectResponse
    {
        return redirect()
            ->route('checkout')
            ->with('error', $message);
    }

    private function redirectToOrder(Order $order, string $type, string $message): RedirectResponse
    {
        $redirect = redirect()->route('orderShow', $order->code);

        return $type === 'success'
            ? $redirect->with('success', $message)
            : $redirect->with('error', $message);
    }
}
