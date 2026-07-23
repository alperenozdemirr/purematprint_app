<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\Status;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as SupportCollection;

class FlexSearchService
{
    public function likeTerm(string $term): string
    {
        return '%'.$term.'%';
    }

    /**
     * @param  Builder<Product>|BelongsToMany<Product, Collection>  $query
     */
    public function applyProductSearch($query, string $term): void
    {
        $like = $this->likeTerm($term);

        $query->where(function (Builder $builder) use ($like) {
            $builder->where('title', 'like', $like)
                ->orWhere('code', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('category', function (Builder $categoryQuery) use ($like) {
                    $categoryQuery->where('name', 'like', $like);
                })
                ->orWhereHas('collections', function (Builder $collectionQuery) use ($like) {
                    $collectionQuery
                        ->where('status', Status::ACTIVE)
                        ->where(function (Builder $matchQuery) use ($like) {
                            $matchQuery->where('title', 'like', $like)
                                ->orWhere('label', 'like', $like)
                                ->orWhere('description', 'like', $like);
                        });
                });
        });
    }

    /**
     * @return SupportCollection<int, Category>
     */
    public function searchCategories(string $term, int $limit = 5): SupportCollection
    {
        return Category::query()
            ->where('name', 'like', $this->likeTerm($term))
            ->orderBy('number')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * @return SupportCollection<int, Collection>
     */
    public function searchCollections(string $term, int $limit = 5): SupportCollection
    {
        return Collection::query()
            ->with('image')
            ->where('status', Status::ACTIVE)
            ->where(function (Builder $query) use ($term) {
                $like = $this->likeTerm($term);

                $query->where('title', 'like', $like)
                    ->orWhere('label', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderBy('number')
            ->orderBy('title')
            ->limit($limit)
            ->get();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildSuggestions(string $term, string $placeholder): array
    {
        $results = [];

        $products = Product::query()
            ->with(['images', 'category'])
            ->where('status', Status::ACTIVE)
            ->where(function (Builder $query) use ($term) {
                $this->applyProductSearch($query, $term);
            })
            ->orderByDesc('featured_status')
            ->orderBy('title')
            ->limit(6)
            ->get();

        foreach ($products as $product) {
            $results[] = [
                'type' => 'product',
                'title' => $product->title,
                'subtitle' => trim(($product->code ?? '').($product->category ? ' · '.$product->category->name : '')),
                'category' => $product->category?->name,
                'code' => $product->code,
                'url' => route('shopDetail', $product->slug),
                'price' => number_format((float) $product->price, 0, ',', '.').' ₺',
                'image' => $product->images->first()?->url ?? $placeholder,
                'in_stock' => $product->stock_count > 0,
            ];
        }

        foreach ($this->searchCategories($term, 3) as $category) {
            $results[] = [
                'type' => 'category',
                'title' => $category->name,
                'subtitle' => 'Kategori',
                'url' => route('shops', ['kategori' => $category->slug]),
                'image' => $placeholder,
            ];
        }

        foreach ($this->searchCollections($term, 3) as $collection) {
            $results[] = [
                'type' => 'collection',
                'title' => $collection->title,
                'subtitle' => $collection->label ?: 'Koleksiyon',
                'url' => route('collectionShow', $collection->slug),
                'image' => $collection->image?->url ?? $placeholder,
            ];
        }

        return $results;
    }
}
