<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductIndexRequest;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Services\FileService;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(ProductIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = Product::query()
            ->with(['category', 'images'])
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

        return view('admin.new-product', compact('categoryOptions'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $code = $this->generateUniqueProductNumber();

        $newProduct = Product::create([
            'title' => $validated['title'],
            'slug' => $this->generateProductSlug($validated['title'], $code),
            'code' => $code,
            'price' => $validated['price'],
            'stock_count' => $validated['stock_count'] ?? 0,
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'featured_status' => $request->boolean('featured_status'),
            'introduction_status' => $request->boolean('introduction_status'),
            'description' => $validated['description'] ?? null,
        ]);

        $number = 0;
        foreach ($request->file('images') ?? [] as $file) {
            $number++;
            $this->fileService->imageUpload($file, ContentType::PRODUCT, $newProduct->id, $number);
        }

        return redirect()->route('admin.productList')->with('success', 'Ürün başarıyla kaydedildi.');
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryOptions = Category::buildSelectOptions();

        return view('admin.product-edit', compact('product', 'categoryOptions'));
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
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'featured_status' => $request->boolean('featured_status'),
            'introduction_status' => $request->boolean('introduction_status'),
            'description' => $validated['description'] ?? null,
        ]);

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

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);

        foreach ($product->images as $image) {
            $this->fileService->imageDelete($image->id, ContentType::PRODUCT);
        }

        $product->delete();

        return redirect()->route('admin.productList')->with('success', 'Ürün başarıyla silindi.');
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
