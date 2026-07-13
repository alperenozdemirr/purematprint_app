<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'number',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function menuTree(): \Illuminate\Support\Collection
    {
        $byParent = self::query()
            ->orderBy('number')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (self $category) => $category->parent_id ?? 0);

        $buildTree = function (?int $parentId = null) use (&$buildTree, $byParent): \Illuminate\Support\Collection {
            return $byParent
                ->get($parentId ?? 0, collect())
                ->map(function (self $category) use ($buildTree) {
                    $category->setRelation('children', $buildTree($category->id));

                    return $category;
                });
        };

        return $buildTree();
    }

    /**
     * @return array<int, array{category: self, depth: int}>
     */
    public function flattenedDescendants(int $depth = 1): array
    {
        $items = [];

        foreach ($this->children as $child) {
            $items[] = ['category' => $child, 'depth' => $depth];
            $items = array_merge($items, $child->flattenedDescendants($depth + 1));
        }

        return $items;
    }

    public function hasNestedChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    /**
     * @return array<int>
     */
    public static function descendantIds(int $categoryId): array
    {
        $byParent = self::query()->get(['id', 'parent_id'])->groupBy('parent_id');
        $ids = [];
        $queue = [$categoryId];

        while ($queue !== []) {
            $current = array_shift($queue);

            foreach ($byParent->get($current, collect()) as $child) {
                $ids[] = $child->id;
                $queue[] = $child->id;
            }
        }

        return $ids;
    }

    /**
     * @return array<int, string>
     */
    public static function parentPathMap(): array
    {
        $all = self::query()->get(['id', 'name', 'parent_id'])->keyBy('id');
        $map = [];

        foreach ($all as $category) {
            $parts = [];
            $parentId = $category->parent_id;

            while ($parentId && isset($all[$parentId])) {
                array_unshift($parts, $all[$parentId]->name);
                $parentId = $all[$parentId]->parent_id;
            }

            $map[$category->id] = $parts !== [] ? implode(' › ', $parts) : '—';
        }

        return $map;
    }

    /**
     * @return array<int, array{id: int, label: string, depth: int}>
     */
    public static function buildSelectOptions(?int $excludeCategoryId = null): array
    {
        $excludeIds = $excludeCategoryId
            ? array_merge([$excludeCategoryId], self::descendantIds($excludeCategoryId))
            : [];

        $byParent = self::query()
            ->orderBy('number')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id'])
            ->groupBy(fn ($category) => $category->parent_id ?? 0);

        $options = [];

        $walk = function (?int $parentId, int $depth = 0) use (&$walk, &$options, $byParent, $excludeIds): void {
            $key = $parentId ?? 0;

            foreach ($byParent->get($key, collect()) as $category) {
                if (in_array($category->id, $excludeIds, true)) {
                    continue;
                }

                $options[] = [
                    'id' => $category->id,
                    'label' => ($depth > 0 ? str_repeat('— ', $depth) : '').$category->name,
                    'depth' => $depth,
                ];

                $walk($category->id, $depth + 1);
            }
        };

        $walk(null);

        return $options;
    }
}
