<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Enums\ContentType;
use App\Enums\ProductPropertyGroupType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductIndexRequest;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Services\FileService;
use App\Http\Services\ProductDeletionService;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use App\Models\ProductPropertyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class ProductController extends Controller
{
    public function __construct(
        protected FileService $fileService,
        protected ProductDeletionService $productDeletionService,
    ) {
    }

    public function index(ProductIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = Product::query()
            ->with(['category', 'images'])
            ->withCount('orderDetails')
            ->latest();

        if (! empty($validated['q'])) {
            $query->where(function ($builder) use ($validated) {
                $builder->where('title', 'like', '%' . $validated['q'] . '%')
                    ->orWhere('code', 'like', '%' . $validated['q'] . '%');
            });
        }

        if (! empty($validated['category'])) {
            $query->where('category_id', $validated['category']);
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $products = $query->paginate(15)->withQueryString();
        $categoryOptions = Category::buildSelectOptions();

        return view('admin.product-list', compact('products', 'categoryOptions'));
    }

    public function storePage(): View
    {
        $categoryOptions = Category::buildSelectOptions();
        $propertyGroupTemplates = $this->propertyGroupTemplates();

        return view('admin.new-product', compact('categoryOptions', 'propertyGroupTemplates'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $code = $this->generateUniqueProductNumber();

        $newProduct = DB::transaction(function () use ($request, $validated, $code) {
            $product = Product::create([
                'title' => $validated['title'],
                'slug' => $this->generateProductSlug($validated['title'], $code),
                'code' => $code,
                'price' => $validated['price'],
                'stock_count' => $validated['stock_count'] ?? 0,
                'shipping_weight' => $validated['shipping_weight'] ?? null,
                'shipping_length' => $validated['shipping_length'] ?? null,
                'shipping_width' => $validated['shipping_width'] ?? null,
                'shipping_height' => $validated['shipping_height'] ?? null,
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'featured_status' => $request->boolean('featured_status'),
                'introduction_status' => $request->boolean('introduction_status'),
                'description' => $validated['description'] ?? null,
            ]);

            $this->storePropertyGroups($product, $validated['property_groups'] ?? []);

            $number = 0;
            foreach ($request->file('images') ?? [] as $file) {
                $number++;
                $this->fileService->imageUpload($file, ContentType::PRODUCT, $product->id, $number);
            }

            return $product;
        });

        return redirect()
            ->route('admin.productEditPage', $newProduct->slug)
            ->with('success', 'Ürün başarıyla kaydedildi. Özellikleri düzenleme ekranından da yönetebilirsiniz.');
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with(['category', 'images', 'propertyGroups.items'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryOptions = Category::buildSelectOptions();

        $propertyGroupTemplates = $this->propertyGroupTemplates();

        return view('admin.product-edit', compact('product', 'categoryOptions', 'propertyGroupTemplates'));
    }

    public function update(ProductUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::query()->findOrFail($validated['id']);

        $product->update([
            'title' => $validated['title'],
            'slug' => $this->generateProductSlug($validated['title'], $product->code),
            'price' => $validated['price'],
            'stock_count' => $validated['stock_count'] ?? 0,
            'shipping_weight' => $validated['shipping_weight'] ?? null,
            'shipping_length' => $validated['shipping_length'] ?? null,
            'shipping_width' => $validated['shipping_width'] ?? null,
            'shipping_height' => $validated['shipping_height'] ?? null,
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'featured_status' => $request->boolean('featured_status'),
            'introduction_status' => $request->boolean('introduction_status'),
            'description' => $validated['description'] ?? null,
        ]);

        $this->reorderExistingImages($product, $validated['existing_image_order'] ?? []);

        $number = (int) File::query()
            ->where('key_id', $product->id)
            ->where('content_type', ContentType::PRODUCT->value)
            ->max('number');

        foreach ($request->file('images') ?? [] as $file) {
            $number++;
            $this->fileService->imageUpload($file, ContentType::PRODUCT, $product->id, $number);
        }

        return redirect()->route('admin.productEditPage', $product->slug)
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function deactivate(int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);

        $this->productDeletionService->deactivate($product);

        return redirect()
            ->route('admin.productList')
            ->with('success', 'Ürün pasife alındı. Vitrinde görünmez; mevcut sipariş kayıtları korunur.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::query()
            ->with(['images'])
            ->findOrFail($id);

        if ($this->productDeletionService->hasOrderHistory($product)) {
            return redirect()
                ->route('admin.productList')
                ->with('error', 'Bu ürün siparişlerde kullanıldığı için silinemez. Pasife alarak vitrinden kaldırabilirsiniz.');
        }

        try {
            $this->productDeletionService->deleteFully($product);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('admin.productList')
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.productList')
            ->with('success', 'Ürün ve tüm bağlantıları kalıcı olarak silindi.');
    }

    public function imageDelete(int $imageId): RedirectResponse
    {
        $image = File::query()->findOrFail($imageId);

        if ($image->content_type !== ContentType::PRODUCT->value) {
            return back()->with('error', 'Geçersiz görsel.');
        }

        $product = Product::query()->findOrFail($image->key_id);

        $this->fileService->imageDelete($imageId, ContentType::PRODUCT);

        return redirect()->route('admin.productEditPage', $product->slug)
            ->with('success', 'Görsel silindi.');
    }

    /**
     * @param  list<int|string>  $orderedIds
     */
    private function reorderExistingImages(Product $product, array $orderedIds): void
    {
        if ($orderedIds === []) {
            return;
        }

        $validIds = File::query()
            ->where('key_id', $product->id)
            ->where('content_type', ContentType::PRODUCT->value)
            ->whereIn('id', $orderedIds)
            ->pluck('id')
            ->all();

        $number = 0;

        foreach ($orderedIds as $imageId) {
            $imageId = (int) $imageId;

            if (! in_array($imageId, $validIds, true)) {
                continue;
            }

            $number++;
            File::query()->whereKey($imageId)->update(['number' => $number]);
        }
    }

    /**
     * @param  list<array<string, mixed>>  $groups
     */
    private function storePropertyGroups(Product $product, array $groups): void
    {
        $groupSort = 0;

        foreach ($groups as $groupData) {
            $groupSort++;
            $type = $groupData['type'] instanceof ProductPropertyGroupType
                ? $groupData['type']
                : ProductPropertyGroupType::from((string) $groupData['type']);

            $group = $product->propertyGroups()->create([
                'title' => trim((string) $groupData['title']),
                'type' => $type,
                'is_required' => (bool) ($groupData['is_required'] ?? false),
                'sort_order' => $groupData['sort_order'] ?? $groupSort,
            ]);

            $itemSort = 0;
            $defaultAssigned = false;

            foreach ($groupData['items'] as $item) {
                $itemSort++;
                $isDefault = (bool) ($item['is_default'] ?? false);

                if ($type === ProductPropertyGroupType::SINGLE && $isDefault) {
                    if ($defaultAssigned) {
                        $isDefault = false;
                    } else {
                        $defaultAssigned = true;
                    }
                }

                $group->items()->create([
                    'title' => trim((string) $item['title']),
                    'price' => $item['price'],
                    'is_default' => $isDefault,
                    'sort_order' => $item['sort_order'] ?? $itemSort,
                ]);
            }
        }
    }

    private function propertyGroupTemplates()
    {
        return ProductPropertyGroup::query()
            ->with(['items', 'product:id,title,code'])
            ->withCount('items')
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(fn (ProductPropertyGroup $group) => [
                'id' => $group->id,
                'title' => $group->title,
                'type' => $group->type->value,
                'is_required' => (bool) $group->is_required,
                'product_title' => $group->product?->title,
                'product_code' => $group->product?->code,
                'items_count' => (int) $group->items_count,
                'items' => $group->items->map(fn ($item) => [
                    'title' => $item->title,
                    'price' => (float) $item->price,
                    'is_default' => (bool) $item->is_default,
                    'sort_order' => (int) $item->sort_order,
                ])->values()->all(),
            ])
            ->values();
    }

    private function generateUniqueProductNumber(): string
    {
        do {
            $length = rand(6, 9);
            $number = str_pad((string) rand(0, (int) pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        } while (Product::query()->where('code', $number)->exists());

        return $number;
    }

    private function generateProductSlug(string $title, string $code): string
    {
        return Str::slug($title) . '-' . $code;
    }
}
