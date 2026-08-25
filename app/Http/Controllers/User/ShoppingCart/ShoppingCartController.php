<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\ShoppingCart;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartPropertiesUpdateRequest;
use App\Http\Requests\User\CartStoreRequest;
use App\Http\Requests\User\CartUpdateRequest;
use App\Http\Services\OrderPricingService;
use App\Http\Services\ProductPropertySelectionService;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ShoppingCartController extends Controller
{
    public function __construct(
        protected OrderPricingService $pricingService,
        protected ProductPropertySelectionService $propertySelection,
    ) {
    }

    public function index(): View
    {
        $cartItems = ShoppingCart::query()
            ->with(['product.images', 'product.propertyGroups.items'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $resolvedByCartId = [];
        foreach ($cartItems as $item) {
            try {
                $resolvedByCartId[$item->id] = $this->propertySelection->resolveFromCartItem($item);
            } catch (ValidationException $e) {
                $resolvedByCartId[$item->id] = [
                    'unit_price' => (float) ($item->product?->price ?? 0),
                    'lines' => [],
                    'invalid' => true,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Seçilen özellikler geçersiz.',
                ];
            }
        }

        $summary = $this->pricingService->calculate($cartItems, auth()->user());

        return view('user.shopping-cart', [
            'cartItems' => $cartItems,
            'resolvedByCartId' => $resolvedByCartId,
            ...$summary,
        ]);
    }

    public function store(CartStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $product = Product::query()
            ->with(['propertyGroups.items'])
            ->where('id', $data['product_id'])
            ->where('status', Status::ACTIVE)
            ->firstOrFail();

        if ($product->stock_count < 1) {
            return back()->with('error', 'Bu ürün stokta yok.');
        }

        $resolved = $this->propertySelection->resolve($product, $data['properties'] ?? []);

        $cartItem = ShoppingCart::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('property_signature', $resolved['signature'])
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
                'selected_property_item_ids' => $resolved['item_ids'],
                'property_signature' => $resolved['signature'],
            ]);
        }

        $afterAction = $data['after_action'] ?? 'cart';

        return redirect()
            ->route($afterAction === 'checkout' ? 'checkout' : 'cart')
            ->with('success', $afterAction === 'checkout' ? 'Ürün sepete eklendi, ödemeye yönlendiriliyorsunuz.' : 'Ürün sepete eklendi.');
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

    public function updateProperties(CartPropertiesUpdateRequest $request, int $id): RedirectResponse
    {
        $cartItem = ShoppingCart::query()
            ->with(['product.propertyGroups.items'])
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;
        if ($product === null || $product->status !== Status::ACTIVE) {
            return back()->with('error', 'Bu ürün artık satışta değil.');
        }

        try {
            $resolved = $this->propertySelection->resolve($product, $request->validated('properties') ?? []);

            DB::transaction(function () use ($cartItem, $resolved, $product) {
                $duplicate = ShoppingCart::query()
                    ->where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->where('property_signature', $resolved['signature'])
                    ->where('id', '!=', $cartItem->id)
                    ->first();

                if ($duplicate) {
                    $mergedQty = (int) $duplicate->quantity + (int) $cartItem->quantity;
                    if ($mergedQty > $product->stock_count) {
                        throw ValidationException::withMessages([
                            'properties' => 'Stok miktarını aştınız. Maksimum '.$product->stock_count.' adet olabilir.',
                        ]);
                    }

                    $duplicate->update(['quantity' => $mergedQty]);
                    $cartItem->delete();

                    return;
                }

                $cartItem->update([
                    'selected_property_item_ids' => $resolved['item_ids'],
                    'property_signature' => $resolved['signature'],
                ]);
            });
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->with('error', collect($e->errors())->flatten()->first() ?? 'Özellik seçimi geçersiz.');
        }

        return redirect()
            ->route('cart')
            ->with('success', 'Ürün özellikleri güncellendi.');
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