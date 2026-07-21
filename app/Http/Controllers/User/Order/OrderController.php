<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutStoreRequest;
use App\Http\Services\OrderPricingService;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected OrderPricingService $pricingService)
    {
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

        foreach ($cartItems as $item) {
            if ($item->product->status !== Status::ACTIVE || $item->product->stock_count < $item->quantity) {
                return back()->with('error', $item->product->title.' için yeterli stok yok.');
            }
        }

        $summary = $this->pricingService->calculate($cartItems, $user);

        $order = DB::transaction(function () use ($user, $cartItems, $addressId, $note, $summary) {
            $order = Order::create([
                'user_id' => $user->id,
                'code' => Order::generateCode(),
                'subtotal' => $summary['subtotal'],
                'is_discount_applied' => $summary['discountApplied'],
                'discount_type' => $summary['discountType'],
                'discount_slice' => (int) round($summary['discountValue'] ?? 0),
                'discount_amount' => $summary['discountAmount'],
                'shipping_is_free' => $summary['shippingFree'],
                'shipping_price' => $summary['shippingCost'],
                'total' => $summary['total'],
                'address_id' => $addressId,
                'invoice_address_id' => $addressId,
                'note' => $note,
                'status' => OrderStatus::PREPARING,
                'invoice_status' => false,
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                Product::query()
                    ->where('id', $item->product_id)
                    ->decrement('stock_count', $item->quantity);
            }

            Payment::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'paid_amount' => $summary['total'],
                'status' => PaymentStatus::COMPLETED,
            ]);

            ShoppingCart::query()->where('user_id', $user->id)->delete();

            return $order;
        });

        return redirect()
            ->route('orderShow', $order->code)
            ->with('success', 'Ödemeniz alındı. Siparişiniz oluşturuldu.');
    }
}
