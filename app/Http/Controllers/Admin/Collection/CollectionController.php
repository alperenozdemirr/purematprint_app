<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Collection;

use App\Enums\ContentType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CollectionStoreRequest;
use App\Http\Requests\Admin\CollectionUpdateRequest;
use App\Http\Services\FileService;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(): View
    {
        $collections = Collection::query()
            ->with('image')
            ->withCount('products')
            ->orderBy('number')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.collection-list', compact('collections'));
    }

    public function storePage(): View
    {
        $products = $this->productOptions();

        return view('admin.new-collection', compact('products'));
    }

    public function store(CollectionStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $collection = Collection::create([
            'title' => $validated['title'],
            'slug' => $this->generateSlug($validated['title']),
            'description' => $validated['description'] ?? null,
            'label' => $validated['label'] ?? null,
            'number' => $validated['number'] ?? 0,
            'status' => $validated['status'],
        ]);

        $fileRecord = $this->fileService->imageUpload(
            $request->file('image'),
            ContentType::COLLECTION,
            $collection->id,
            1
        );

        $collection->update(['image_id' => $fileRecord->id]);

        $this->syncProducts($collection, $validated['product_ids'] ?? []);

        return redirect()->route('admin.collectionList')->with('success', 'Koleksiyon başarıyla kaydedildi.');
    }

    public function show(int $id): View
    {
        $collection = Collection::query()
            ->with(['image', 'products'])
            ->findOrFail($id);

        $products = $this->productOptions();
        $selectedProductIds = $collection->products->pluck('id')->all();

        return view('admin.collection-edit', compact('collection', 'products', 'selectedProductIds'));
    }

    public function update(CollectionUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $collection = Collection::query()->findOrFail((int) $validated['id']);

        $collection->update([
            'title' => $validated['title'],
            'slug' => $collection->title !== $validated['title']
                ? $this->generateSlug($validated['title'], $collection->id)
                : $collection->slug,
            'description' => $validated['description'] ?? null,
            'label' => $validated['label'] ?? null,
            'number' => $validated['number'] ?? 0,
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('image')) {
            if ($collection->image_id) {
                $this->fileService->imageDelete($collection->image_id, ContentType::COLLECTION);
            }

            $fileRecord = $this->fileService->imageUpload(
                $request->file('image'),
                ContentType::COLLECTION,
                $collection->id,
                1
            );

            $collection->update(['image_id' => $fileRecord->id]);
        }

        $this->syncProducts($collection, $validated['product_ids'] ?? []);

        return redirect()->route('admin.collectionEditPage', $collection->id)
            ->with('success', 'Koleksiyon başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $collection = Collection::query()->findOrFail($id);

        if ($collection->image_id) {
            $this->fileService->imageDelete($collection->image_id, ContentType::COLLECTION);
        }

        $collection->products()->detach();
        $collection->delete();

        return redirect()->route('admin.collectionList')->with('success', 'Koleksiyon silindi.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>
     */
    private function productOptions()
    {
        return Product::query()
            ->with('images')
            ->orderBy('title')
            ->get();
    }

    /**
     * @param  array<int, int|string>  $productIds
     */
    private function syncProducts(Collection $collection, array $productIds): void
    {
        $syncData = [];

        foreach (array_values(array_unique(array_map('intval', $productIds))) as $index => $productId) {
            if ($productId > 0) {
                $syncData[$productId] = ['number' => $index];
            }
        }

        $collection->products()->sync($syncData);
    }

    private function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Collection::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
