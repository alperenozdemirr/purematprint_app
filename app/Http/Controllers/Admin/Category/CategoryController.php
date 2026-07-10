<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with('parent')
            ->withCount(['products', 'children'])
            ->orderBy('number')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.category-list', compact('categories'));
    }

    public function storePage(): View
    {
        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.new-category', compact('parentCategories'));
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Category::create([
            'name' => $validated['name'],
            'slug' => $this->generateCategorySlug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
            'number' => $validated['number'] ?? 0,
        ]);

        return redirect()->route('admin.categoryList')->with('success', 'Kategori başarıyla kaydedildi.');
    }

    public function show(string $slug): View
    {
        $category = Category::query()
            ->with('parent')
            ->where('slug', $slug)
            ->firstOrFail();

        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.category-edit', compact('category', 'parentCategories'));
    }

    public function update(CategoryUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $category = Category::query()->findOrFail($validated['id']);

        $category->update([
            'name' => $validated['name'],
            'slug' => $category->name !== $validated['name']
                ? $this->generateCategorySlug($validated['name'], $category->id)
                : $category->slug,
            'parent_id' => $validated['parent_id'] ?? null,
            'number' => $validated['number'] ?? 0,
        ]);

        return redirect()->route('admin.categoryEditPage', $category->slug)
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = Category::query()->withCount(['products', 'children'])->findOrFail($id);

        if ($category->products_count > 0) {
            return back()->with('error', 'Bu kategoriye bağlı ürünler var. Önce ürünleri taşıyın veya silin.');
        }

        if ($category->children_count > 0) {
            return back()->with('error', 'Bu kategorinin alt kategorileri var. Önce alt kategorileri silin.');
        }

        $category->delete();

        return redirect()->route('admin.categoryList')->with('success', 'Kategori başarıyla silindi.');
    }

    private function generateCategorySlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        do {
            $length = random_int(6, 9);
            $min = (int) pow(10, $length - 1);
            $max = (int) pow(10, $length) - 1;
            $suffix = (string) random_int($min, $max);
            $slug = $baseSlug . '-' . $suffix;
        } while (Category::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists());

        return $slug;
    }
}
