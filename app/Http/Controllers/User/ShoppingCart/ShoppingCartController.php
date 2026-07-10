<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\ShoppingCart;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartStoreRequest;
use App\Http\Requests\User\CartUpdateRequest;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShoppingCartController extends Controller
{
    private const FREE_SHIPPING_MIN = 500;

    private const SHIPPING_FEE = 49;

    public function index(): View
    {
        $cartItems = ShoppingCart::query()
            ->with(['product.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $summary = $this->buildSummary($cartItems);

        return view('user.shopping-cart', [
            'cartItems' => $cartItems,
            ...$summary,
        ]);
    }

    public function store(CartStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $product = Product::query()
            ->where('id', $data['product_id'])
            ->where('status', Status::ACTIVE)
            ->firstOrFail();

        if ($product->stock_count < 1) {
            return back()->with('error', 'Bu ürün stokta yok.');
        }

        $cartItem = ShoppingCart::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = ($cartItem?->quantity ?? 0) + (int) $data['quantity'];

        if ($newQuantity > $product->stock_count) {
            return back()->with('error', 'Stok miktarını aştınız. Maksimum '.$product->stock_count.' adet ekleyebilirsiniz.');
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            ShoppingCart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);
        }

        return redirect()
            ->route('cart')
            ->with('success', 'Ürün sepete eklendi.');
    }

    public function update(CartUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $cartItem = ShoppingCart::query()
            ->with('product')
            ->where('user_id', auth()->id())
            ->where('id', $data['id'])
            ->firstOrFail();

        if ($data['quantity'] > $cartItem->product->stock_count) {
            return back()->with('error', 'Stok miktarını aştınız. Maksimum '.$cartItem->product->stock_count.' adet ekleyebilirsiniz.');
        }

        $cartItem->update(['quantity' => $data['quantity']]);

        return redirect()
            ->route('cart')
            ->with('success', 'Sepet güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        ShoppingCart::query()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('cart')
            ->with('success', 'Ürün sepetten kaldırıldı.');
    }

  /**
     * @param  \Illuminate\Support\Collection<int, ShoppingCart>  $cartItems
     * @return array{subtotal: float, shippingCost: float, total: float, totalQty: int, shippingFree: bool, shippingRemaining: float}
     */
    private function buildSummary($cartItems): array
    {
        $subtotal = 0.0;
        $totalQty = 0;

        foreach ($cartItems as $item) {
            $subtotal += (float) $item->product->price * $item->quantity;
            $totalQty += $item->quantity;
        }

        $shippingFree = $subtotal >= self::FREE_SHIPPING_MIN;
        $shippingCost = $shippingFree ? 0.0 : ($cartItems->isEmpty() ? 0.0 : (float) self::SHIPPING_FEE);
        $total = $subtotal + $shippingCost;
        $shippingRemaining = max(0, self::FREE_SHIPPING_MIN - $subtotal);

        return compact('subtotal', 'shippingCost', 'total', 'totalQty', 'shippingFree', 'shippingRemaining');
    }
}
