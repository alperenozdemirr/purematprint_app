<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Product;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProductIndexRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = Product::query()
            ->with(['category', 'images'])
            ->where('status', Status::ACTIVE);

        if (! empty($validated['kategori'])) {
            $category = Category::query()
                ->where('slug', $validated['kategori'])
                ->first();

            if ($category) {
                $categoryIds = Category::query()
                    ->where('parent_id', $category->id)
                    ->pluck('id')
                    ->push($category->id);

                $query->whereIn('category_id', $categoryIds);
            }
        }

        match ($validated['siralama'] ?? 'featured') {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('title'),
            default => $query->orderByDesc('featured_status')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->whereNull('parent_id')
            ->orderBy('number')
            ->orderBy('name')
            ->get();

        return view('user.shops', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with(['category.parent', 'images'])
            ->where('slug', $slug)
            ->where('status', Status::ACTIVE)
            ->firstOrFail();

        $categoryFilter = $product->category?->parent ?? $product->category;

        $relatedProducts = Product::query()
            ->with('images')
            ->where('status', Status::ACTIVE)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('featured_status')
            ->latest()
            ->limit(8)
            ->get();

        return view('user.shop-detail', compact('product', 'categoryFilter', 'relatedProducts'));
    }

    public function collectionList(): View
    {
        return view('user.collection-list');
    }
}
