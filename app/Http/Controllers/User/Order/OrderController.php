<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutStoreRequest;
use App\Http\Services\CheckoutDraftService;
use App\Http\Services\IyzicoPaymentService;
use App\Http\Services\OrderPricingService;
use App\Mail\OrderConfirmationMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected OrderPricingService $pricingService,
        protected IyzicoPaymentService $iyzicoService,
        protected CheckoutDraftService $draftService,
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
                'details.comment',
                'payment',
            ])
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->firstOrFail();

        return view('user.default.order-detail', [
            'order' => $order,
            'activeNav' => 'orders',
        ]);
    }

    public function checkoutPage(): View|RedirectResponse
    {
        $cartItems = ShoppingCart::query()
            ->with(['product.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Sepetiniz boş. Ödeme yapabilmek için sepete ürün ekleyin.');
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

        return view('user.default.checkout', [
            'cartItems' => $cartItems,
            'addresses' => $addresses,
            'user' => $user,
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
            ->with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Sepetiniz boş.');
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

        if (! $this->iyzicoService->isConfigured()) {
            return back()->with('error', 'Ödeme sistemi henüz yapılandırılmamış. Lütfen daha sonra tekrar deneyin.');
        }

        $address = Address::query()
            ->with(['city', 'county'])
            ->where('user_id', $user->id)
            ->findOrFail($addressId);

        $draft = $this->draftService->createDraft(
            $user,
            $addressId,
            $note,
            $invoiceAttributes,
            $summary,
            $cartItems,
        );

        $initialize = $this->iyzicoService->initializeCheckoutFromDraft(
            $draft,
            $user,
            $address,
            $cartItems,
            $request->ip() ?? '127.0.0.1',
        );

        if (! $initialize['success'] || empty($initialize['token'])) {
            return back()->with('error', $initialize['error'] ?? 'Ödeme sayfası oluşturulamadı.');
        }

        $this->draftService->store($initialize['token'], $draft);

        return redirect()->away($initialize['paymentPageUrl']);
    }
}
