<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\PaymentProvider;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutStoreRequest;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\CheckoutOrderFileService;
use App\Http\Services\IyzicoPaymentService;
use App\Http\Services\OrderFileDownloadService;
use App\Http\Services\OrderPricingService;
use App\Http\Services\ProductPropertySelectionService;
use App\Http\Services\StripePaymentService;
use App\Models\Address;
use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        protected OrderPricingService $pricingService,
        protected IyzicoPaymentService $iyzicoService,
        protected StripePaymentService $stripeService,
        protected CheckoutDraftService $draftService,
        protected CheckoutOrderFileService $orderFileService,
        protected OrderFileDownloadService $orderFileDownloadService,
        protected ProductPropertySelectionService $propertySelection,
    ) {
    }

    public function index(): View
    {
        $orders = Order::query()
            ->with(['details.product.images', 'details.comment', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.default.order-list', [
            'orders' => $orders,
            'activeNav' => 'orders',
        ]);
    }

    public function show(string $code): View
    {
        $order = Order::query()
            ->with([
                'address.city',
                'address.county',
                'details.product.images',
                'details.comment.images',
                'details.properties',
                'payment',
                'orderFiles',
                'invoiceFile',
            ])
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();

        return view('user.default.order-detail', [
            'order' => $order,
            'activeNav' => 'orders',
        ]);
    }

    public function downloadFile(string $code, int $fileId): StreamedResponse
    {
        $order = Order::query()
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();

        return $this->orderFileDownloadService->download($order, $fileId);
    }

    public function checkoutPage(): View|RedirectResponse
    {
        $cartItems = ShoppingCart::query()
            ->with(['product.images', 'product.propertyGroups.items'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Sepetiniz boş. Ödeme yapabilmek için sepete ürün ekleyin.');
        }

        try {
            $this->propertySelection->assertCartStillValid($cartItems);
        } catch (ValidationException $e) {
            return redirect()
                ->route('cart')
                ->with('error', collect($e->errors())->flatten()->first() ?? 'Sepetinizdeki ürün özelliklerini kontrol edin.');
        }

        $user = auth()->user();

        if (empty(trim($user->phone ?? ''))) {
            return redirect()
                ->route('account')
                ->with('error', 'Ödeme yapabilmek için profil bilgilerinizde telefon numaranızı tamamlayın.');
        }

        $addresses = Address::query()
            ->with(['city', 'county'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        if ($addresses->isEmpty()) {
            return redirect()
                ->route('addressCreatePage')
                ->with('error', 'Ödeme yapabilmek için en az bir teslimat adresi ekleyin.');
        }

        $summary = $this->pricingService->calculate($cartItems, $user);

        $resolvedByCartId = [];
        foreach ($cartItems as $item) {
            $resolvedByCartId[$item->id] = $this->propertySelection->resolveFromCartItem($item);
        }

        return view('user.default.checkout', [
            'cartItems' => $cartItems,
            'addresses' => $addresses,
            'user' => $user,
            'resolvedByCartId' => $resolvedByCartId,
            ...$summary,
        ]);
    }

    public function checkoutStore(CheckoutStoreRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (empty(trim($user->phone ?? ''))) {
            return redirect()
                ->route('account')
                ->with('error', 'Ödeme yapabilmek için telefon numaranızı profilinize ekleyin.');
        }

        $cartItems = ShoppingCart::query()
            ->with(['product.propertyGroups.items'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Sepetiniz boş.');
        }

        try {
            $this->propertySelection->assertCartStillValid($cartItems);
        } catch (ValidationException $e) {
            return redirect()
                ->route('cart')
                ->with('error', collect($e->errors())->flatten()->first() ?? 'Sepetinizdeki ürün özelliklerini kontrol edin.');
        }

        $addressId = (int) $request->validated('address_id');
        $note = $request->validated('note');
        $invoiceAttributes = $request->invoiceAttributes();

        foreach ($cartItems as $item) {
            if ($item->product->status !== Status::ACTIVE || $item->product->stock_count < $item->quantity) {
                return back()->with('error', $item->product->title.' için yeterli stok yok.');
            }
        }

        $summary = $this->pricingService->calculate($cartItems, $user);

        $address = Address::query()
            ->with(['city', 'county'])
            ->where('user_id', $user->id)
            ->findOrFail($addressId);

        if ($address->isInternational()) {
            return $this->checkoutWithStripe($request, $user, $address, $cartItems, $summary, $addressId, $note, $invoiceAttributes);
        }

        return $this->checkoutWithIyzico($request, $user, $address, $cartItems, $summary, $addressId, $note, $invoiceAttributes);
    }

    private function checkoutWithIyzico(
        CheckoutStoreRequest $request,
        $user,
        Address $address,
        $cartItems,
        array $summary,
        int $addressId,
        ?string $note,
        array $invoiceAttributes,
    ): RedirectResponse {
        if (! $this->iyzicoService->isConfigured()) {
            return back()->with('error', 'Yurt içi ödeme sistemi henüz yapılandırılmamış.');
        }

        $draft = $this->draftService->createDraft(
            $user,
            $addressId,
            $note,
            $invoiceAttributes,
            $summary,
            $cartItems,
            PaymentProvider::IYZICO,
        );

        $draft['files'] = $this->orderFileService->storeTemporary(
            $draft['draft_id'],
            $request->file('order_files')
        );

        $initialize = $this->iyzicoService->initializeCheckoutFromDraft(
            $draft,
            $user,
            $address,
            $cartItems,
            $request->ip() ?? '127.0.0.1',
        );

        if (! $initialize['success'] || empty($initialize['token'])) {
            $this->orderFileService->cleanupDraftFiles($draft);

            return back()->with('error', $initialize['error'] ?? 'Ödeme sayfası oluşturulamadı.');
        }

        $this->draftService->store(PaymentProvider::IYZICO, $initialize['token'], $draft);

        return redirect()->away($initialize['paymentPageUrl']);
    }

    private function checkoutWithStripe(
        CheckoutStoreRequest $request,
        $user,
        Address $address,
        $cartItems,
        array $summary,
        int $addressId,
        ?string $note,
        array $invoiceAttributes,
    ): RedirectResponse {
        if (! $this->stripeService->isConfigured()) {
            return back()->with('error', 'Yurt dışı ödeme sistemi (Stripe) henüz yapılandırılmamış.');
        }

        $draft = $this->draftService->createDraft(
            $user,
            $addressId,
            $note,
            $invoiceAttributes,
            $summary,
            $cartItems,
            PaymentProvider::STRIPE,
        );

        $draft['files'] = $this->orderFileService->storeTemporary(
            $draft['draft_id'],
            $request->file('order_files')
        );

        $session = $this->stripeService->createCheckoutSession($draft, $user, $cartItems);

        if (! $session['success'] || empty($session['sessionId']) || empty($session['checkoutUrl'])) {
            $this->orderFileService->cleanupDraftFiles($draft);

            return back()->with('error', $session['error'] ?? 'Stripe ödeme sayfası oluşturulamadı.');
        }

        $this->draftService->store(PaymentProvider::STRIPE, $session['sessionId'], $draft);

        return redirect()->away($session['checkoutUrl']);
    }
}
