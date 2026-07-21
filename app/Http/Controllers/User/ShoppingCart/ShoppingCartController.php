<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\ShoppingCart;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartStoreRequest;
use App\Http\Requests\User\CartUpdateRequest;
use App\Http\Services\OrderPricingService;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShoppingCartController extends Controller
{
    public function __construct(protected OrderPricingService $pricingService)
    {
    }

    public function index(): View
    {
        $cartItems = ShoppingCart::query()
            ->with(['product.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $summary = $this->pricingService->calculate($cartItems, auth()->user());

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
}
