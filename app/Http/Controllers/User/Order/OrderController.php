<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\PaymentProvider;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutStoreRequest;
use App\Http\Requests\User\OrderCustomerFileUploadRequest;
use App\Http\Requests\User\OrderDesignDecisionRequest;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\CheckoutOrderFileService;
use App\Http\Services\IyzicoPaymentService;
use App\Http\Services\OrderCancellationService;
use App\Http\Services\OrderFileDownloadService;
use App\Http\Services\OrderPreparingFileService;
use App\Http\Services\OrderPricingService;
use App\Http\Services\ProductPropertySelectionService;
use App\Http\Services\StripePaymentService;
use App\Models\Address;
use App\Models\File;
use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        protected OrderPreparingFileService $preparingFileService,
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
                'designFile',
                'designRequests.file',
            ])
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();

        return view('user.default.order-detail', [
            'order' => $order,
            'activeNav' => 'orders',
            'canManagePreparing' => $order->canManageOrderFilesAndDesign(),
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

    public function uploadCustomerFile(OrderCustomerFileUploadRequest $request, string $code): RedirectResponse
    {
        $order = $this->ownedOrder($code);

        try {
            $this->preparingFileService->queueCustomerFileUpload($order, $request->file('file'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('success', 'Dosya yükleme kuyruğa alındı. Kısa süre içinde işlenecek ve e-posta ile bilgilendirileceksiniz.');
    }

    public function deleteCustomerFile(string $code, int $fileId): RedirectResponse
    {
        $order = $this->ownedOrder($code);
        $file = File::query()->findOrFail($fileId);

        try {
            $this->preparingFileService->queueCustomerFileDelete($order, $file);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Dosya silme işlemi kuyruğa alındı. Tamamlandığında e-posta ile bilgilendirileceksiniz.');
    }

    public function decideDesign(OrderDesignDecisionRequest $request, string $code): RedirectResponse
    {
        $order = $this->ownedOrder($code)->load('designFile');
        $validated = $request->validated();
        $note = isset($validated['note']) ? trim((string) $validated['note']) : null;
        $note = $note !== '' ? $note : null;

        try {
            if ($validated['decision'] === 'approve') {
                $this->preparingFileService->approveDesign($order, $note, (int) auth()->id());
            } else {
                $this->preparingFileService->requestDesignRevision($order, (string) $note, (int) auth()->id());
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with(
            'success',
            $validated['decision'] === 'approve'
                ? 'Tasarım onayınız kaydedildi. Bilgilendirme e-postası gönderilecek.'
                : 'Revize talebiniz kaydedildi. Bilgilendirme e-postası gönderilecek.'
        );
    }

    public function cancel(Request $request, string $code, OrderCancellationService $cancellationService): RedirectResponse
    {
        $order = $this->ownedOrder($code)->load('payment');

        try {
            $cancellationService->requestSelfCancellation(
                $order,
                $request->ip() ?? '127.0.0.1',
            );
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return back()->with(
            'success',
            'İptal talebiniz alındı. Ödemeniz iade edilecek ve e-posta ile bilgilendirileceksiniz.',
        );
    }

    private function ownedOrder(string $code): Order
    {
        return Order::query()
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();
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
        $designType = (string) $request->validated('design_type');
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
            return $this->checkoutWithStripe($request, $user, $address, $cartItems, $summary, $addressId, $note, $invoiceAttributes, $designType);
        }

        return $this->checkoutWithIyzico($request, $user, $address, $cartItems, $summary, $addressId, $note, $invoiceAttributes, $designType);
    }

    public function reorder(string $code): RedirectResponse
    {
        $order = Order::query()
            ->with(['details.product.propertyGroups.items', 'details.properties'])
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();

        $added = 0;
        $skipped = [];

        foreach ($order->details as $detail) {
            $product = $detail->product;

            if ($product === null || $product->status !== Status::ACTIVE) {
                $skipped[] = ($product?->title ?? 'Ürün').' artık satışta değil.';
                continue;
            }

            if ($product->stock_count < 1) {
                $skipped[] = $product->title.' stokta yok.';
                continue;
            }

            $rawProperties = [];
            foreach ($detail->properties as $property) {
                $itemId = (int) ($property->property_item_id ?? 0);
                if ($itemId < 1) {
                    continue;
                }

                $item = $product->propertyGroups
                    ->flatMap(fn ($group) => $group->items)
                    ->firstWhere('id', $itemId);

                if ($item === null) {
                    $skipped[] = $product->title.' için eski özellikler artık geçerli değil.';
                    continue 2;
                }

                $groupId = (int) $item->group_id;
                $rawProperties[$groupId] ??= [];
                $rawProperties[$groupId][] = $itemId;
            }

            try {
                $resolved = $this->propertySelection->resolve($product, $rawProperties);
            } catch (ValidationException $e) {
                $skipped[] = $product->title.': '.(collect($e->errors())->flatten()->first() ?? 'özellikler geçersiz.');
                continue;
            }

            $quantity = min((int) $detail->quantity, (int) $product->stock_count);

            $cartItem = ShoppingCart::query()
                ->where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('property_signature', $resolved['signature'])
                ->first();

            $newQuantity = ($cartItem?->quantity ?? 0) + $quantity;

            if ($newQuantity > $product->stock_count) {
                $newQuantity = (int) $product->stock_count;
            }

            if ($newQuantity < 1) {
                $skipped[] = $product->title.' için yeterli stok yok.';
                continue;
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                ShoppingCart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $newQuantity,
                    'selected_property_item_ids' => $resolved['item_ids'],
                    'property_signature' => $resolved['signature'],
                ]);
            }

            $added++;
        }

        if ($added < 1) {
            return back()->with('error', $skipped[0] ?? 'Siparişteki ürünler sepete eklenemedi.');
        }

        $message = $added.' ürün sepete eklendi.';
        if ($skipped !== []) {
            $message .= ' Bazı ürünler atlandı: '.implode(' ', array_slice($skipped, 0, 3));
        }

        return redirect()->route('cart')->with('success', $message);
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
        string $designType,
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
            $designType,
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
        string $designType,
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
            $designType,
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
