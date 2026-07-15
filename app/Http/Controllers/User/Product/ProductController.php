<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Product;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProductIndexRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
                $categoryIds = collect(Category::descendantIds($category->id))
                    ->push($category->id);

                $query->whereIn('category_id', $categoryIds);
            }
        }

        if (! empty($validated['q'])) {
            $this->applySearchTerm($query, $validated['q']);
        }

        $this->applySorting($query, $validated['siralama'] ?? 'featured');

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
        $collections = Collection::query()
            ->with('image')
            ->withCount('products')
            ->where('status', Status::ACTIVE)
            ->orderBy('number')
            ->orderBy('title')
            ->get();

        return view('user.collection-list', compact('collections'));
    }

    public function collectionShow(string $slug, ProductIndexRequest $request): View
    {
        $validated = $request->validated();

        $collection = Collection::query()
            ->with('image')
            ->where('slug', $slug)
            ->where('status', Status::ACTIVE)
            ->firstOrFail();

        $query = $collection->products()
            ->with(['category', 'images'])
            ->where('status', Status::ACTIVE);

        if (! empty($validated['q'])) {
            $this->applySearchTerm($query, $validated['q']);
        }

        $this->applySorting($query, $validated['siralama'] ?? 'featured');

        $products = $query->paginate(12)->withQueryString();

        $otherCollections = Collection::query()
            ->with('image')
            ->where('status', Status::ACTIVE)
            ->where('id', '!=', $collection->id)
            ->orderBy('number')
            ->orderBy('title')
            ->limit(3)
            ->get();

        return view('user.collection-product', compact('collection', 'products', 'otherCollections'));
    }

    public function searchSuggestions(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $placeholder = asset('user/assets/foto5.jpeg');

        $results = Product::query()
            ->with('images')
            ->where('status', Status::ACTIVE)
            ->where(function ($query) use ($term) {
                $this->applySearchTerm($query, $term);
            })
            ->orderByDesc('featured_status')
            ->orderBy('title')
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'title' => $product->title,
                'code' => $product->code,
                'url' => route('shopDetail', $product->slug),
                'price' => number_format((float) $product->price, 0, ',', '.').' ₺',
                'image' => $product->images->first()?->url ?? $placeholder,
                'in_stock' => $product->stock_count > 0,
            ]);

        return response()->json([
            'results' => $results,
            'total_url' => route('shops', ['q' => $term]),
        ]);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Product>|\Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Collection>  $query
     */
    private function applySearchTerm($query, string $term): void
    {
        $like = '%'.$term.'%';

        $query->where(function ($builder) use ($like) {
            $builder->where('title', 'like', $like)
                ->orWhere('code', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Product>|\Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Collection>  $query
     */
    private function applySorting($query, string $sort): void
    {
        match ($sort) {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('title'),
            default => $query->orderByDesc('featured_status')->latest(),
        };
    }
}
