<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Product;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProductIndexRequest;
use App\Http\Services\FlexSearchService;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected FlexSearchService $flexSearch)
    {
    }

    public function index(ProductIndexRequest $request): View|RedirectResponse
    {
        if ($request->filled('kategori')) {
            $params = array_filter([
                'page' => $request->query('page'),
                'siralama' => $request->query('siralama'),
                'q' => $request->query('q'),
            ], fn ($value) => $value !== null && $value !== '');

            return redirect()->route(
                'categoryShow',
                array_merge(['slug' => $request->query('kategori')], $params),
                301,
            );
        }

        return $this->renderShops($request, null);
    }

    public function category(string $slug, ProductIndexRequest $request): View
    {
        $activeCategory = Category::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->renderShops($request, $activeCategory);
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with([
                'category.parent',
                'images',
                'propertyGroups.items',
                'comments' => fn ($query) => $query
                    ->where('is_visible', true)
                    ->with(['user', 'images'])
                    ->latest(),
            ])
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

        $productReviews = $product->comments;
        $defaultPropertySelections = app(\App\Http\Services\ProductPropertySelectionService::class)
            ->defaultSelections($product);

        $homepageFaqs = Faq::query()
            ->fixed()
            ->ordered()
            ->get();

        $cartOtherItemsCount = 0;
        $user = auth()->user();
        if ($user && $user->type === UserType::USER && $user->status === Status::ACTIVE) {
            $cartOtherItemsCount = ShoppingCart::query()
                ->where('user_id', $user->id)
                ->where('product_id', '!=', $product->id)
                ->count();
        }

        return view('user.shop-detail', compact(
            'product',
            'categoryFilter',
            'relatedProducts',
            'productReviews',
            'defaultPropertySelections',
            'homepageFaqs',
            'cartOtherItemsCount',
        ));
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
            $this->flexSearch->applyProductSearch($query, $validated['q']);
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

        return response()->json([
            'results' => $this->flexSearch->buildSuggestions($term, $placeholder),
            'total_url' => route('shops', ['q' => $term]),
        ]);
    }

    private function renderShops(ProductIndexRequest $request, ?Category $activeCategory): View
    {
        $validated = $request->validated();

        $query = Product::query()
            ->with(['category', 'images'])
            ->where('status', Status::ACTIVE);

        $searchCategories = collect();
        $searchCollections = collect();

        if ($activeCategory !== null) {
            $categoryIds = collect(Category::descendantIds($activeCategory->id))
                ->push($activeCategory->id);

            $query->whereIn('category_id', $categoryIds);
        }

        if (! empty($validated['q'])) {
            $this->flexSearch->applyProductSearch($query, $validated['q']);
            $searchCategories = $this->flexSearch->searchCategories($validated['q'], 5);
            $searchCollections = $this->flexSearch->searchCollections($validated['q'], 5);
        }

        $this->applySorting($query, $validated['siralama'] ?? 'featured');

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->whereNull('parent_id')
            ->orderBy('number')
            ->orderBy('name')
            ->get();

        return view('user.shops', compact('products', 'categories', 'searchCategories', 'searchCollections', 'activeCategory'));
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
