<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Enums\ProductPropertyGroupType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductPropertyGroupStoreRequest;
use App\Http\Requests\Admin\ProductPropertyGroupUpdateRequest;
use App\Http\Requests\Admin\ProductPropertyGroupWithItemsStoreRequest;
use App\Http\Requests\Admin\ProductPropertyItemBulkStoreRequest;
use App\Http\Requests\Admin\ProductPropertyItemBulkUpdateRequest;
use App\Http\Requests\Admin\ProductPropertyItemStoreRequest;
use App\Http\Requests\Admin\ProductPropertyItemUpdateRequest;
use App\Models\Product;
use App\Models\ProductPropertyGroup;
use App\Models\ProductPropertyItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductPropertyController extends Controller
{
    public function storeGroup(ProductPropertyGroupStoreRequest $request, int $productId): RedirectResponse
    {
        $product = Product::query()->findOrFail($productId);
        $data = $request->validated();

        $maxSort = (int) $product->propertyGroups()->max('sort_order');

        $product->propertyGroups()->create([
            'title' => trim($data['title']),
            'type' => $data['type'],
            'is_required' => (bool) ($data['is_required'] ?? false),
            'sort_order' => $data['sort_order'] ?? ($maxSort + 1),
        ]);

        return back()->with('success', 'Özellik grubu eklendi.');
    }

    public function storeGroupWithItems(ProductPropertyGroupWithItemsStoreRequest $request, int $productId): RedirectResponse
    {
        $product = Product::query()->findOrFail($productId);
        $data = $request->validated();
        $type = $data['type'] instanceof ProductPropertyGroupType
            ? $data['type']
            : ProductPropertyGroupType::from((string) $data['type']);

        DB::transaction(function () use ($product, $data, $type) {
            $maxSort = (int) $product->propertyGroups()->max('sort_order');

            $group = $product->propertyGroups()->create([
                'title' => trim((string) $data['title']),
                'type' => $type,
                'is_required' => (bool) ($data['is_required'] ?? false),
                'sort_order' => $data['sort_order'] ?? ($maxSort + 1),
            ]);

            $itemSort = 0;
            $defaultAssigned = false;

            foreach ($data['items'] as $item) {
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
        });

        return back()->with('success', 'Özellik grubu ve seçenekleri tek seferde eklendi.');
    }

    public function updateGroup(ProductPropertyGroupUpdateRequest $request, int $groupId): RedirectResponse
    {
        $group = ProductPropertyGroup::query()->with('items')->findOrFail($groupId);
        $data = $request->validated();
        $newType = $data['type'] instanceof ProductPropertyGroupType
            ? $data['type']
            : ProductPropertyGroupType::from((string) $data['type']);

        if ((bool) ($data['is_required'] ?? false) && $group->items->isEmpty()) {
            return back()->with('error', 'Zorunlu grup için önce en az bir seçenek ekleyin.');
        }

        if (
            $newType === ProductPropertyGroupType::SINGLE
            && $group->items->where('is_default', true)->count() > 1
        ) {
            $keepId = $group->items->where('is_default', true)->sortBy('sort_order')->first()?->id;
            ProductPropertyItem::query()
                ->where('group_id', $group->id)
                ->where('is_default', true)
                ->when($keepId, fn ($q) => $q->where('id', '!=', $keepId))
                ->update(['is_default' => false]);
        }

        $group->update([
            'title' => trim($data['title']),
            'type' => $newType,
            'is_required' => (bool) ($data['is_required'] ?? false),
            'sort_order' => $data['sort_order'] ?? $group->sort_order,
        ]);

        return back()->with('success', 'Özellik grubu güncellendi.');
    }

    public function destroyGroup(int $groupId): RedirectResponse
    {
        $group = ProductPropertyGroup::query()->findOrFail($groupId);
        $group->delete();

        return back()->with('success', 'Özellik grubu silindi.');
    }

    public function storeItem(ProductPropertyItemStoreRequest $request, int $groupId): RedirectResponse
    {
        $group = ProductPropertyGroup::query()->findOrFail($groupId);
        $data = $request->validated();
        $isDefault = (bool) ($data['is_default'] ?? false);

        DB::transaction(function () use ($group, $data, $isDefault) {
            if ($isDefault && $group->type === ProductPropertyGroupType::SINGLE) {
                ProductPropertyItem::query()
                    ->where('group_id', $group->id)
                    ->update(['is_default' => false]);
            }

            $maxSort = (int) $group->items()->max('sort_order');

            $group->items()->create([
                'title' => trim($data['title']),
                'price' => $data['price'],
                'is_default' => $isDefault,
                'sort_order' => $data['sort_order'] ?? ($maxSort + 1),
            ]);
        });

        return back()->with('success', 'Seçenek eklendi.');
    }

    public function storeItemsBulk(ProductPropertyItemBulkStoreRequest $request, int $groupId): RedirectResponse
    {
        $group = ProductPropertyGroup::query()->findOrFail($groupId);
        $items = $request->validated('items');
        $created = 0;

        DB::transaction(function () use ($group, $items, &$created) {
            $maxSort = (int) $group->items()->max('sort_order');
            $hasDefaultInPayload = collect($items)->contains(fn (array $item) => (bool) ($item['is_default'] ?? false));

            if ($hasDefaultInPayload && $group->type === ProductPropertyGroupType::SINGLE) {
                ProductPropertyItem::query()
                    ->where('group_id', $group->id)
                    ->update(['is_default' => false]);
            }

            $defaultAssigned = false;

            foreach ($items as $item) {
                $isDefault = (bool) ($item['is_default'] ?? false);

                if ($group->type === ProductPropertyGroupType::SINGLE && $isDefault) {
                    if ($defaultAssigned) {
                        $isDefault = false;
                    } else {
                        $defaultAssigned = true;
                    }
                }

                $maxSort++;

                $group->items()->create([
                    'title' => trim((string) $item['title']),
                    'price' => $item['price'],
                    'is_default' => $isDefault,
                    'sort_order' => $item['sort_order'] ?? $maxSort,
                ]);

                $created++;
            }
        });

        return back()->with('success', $created.' seçenek toplu olarak eklendi.');
    }

    public function updateItemsBulk(ProductPropertyItemBulkUpdateRequest $request, int $groupId): RedirectResponse
    {
        $group = ProductPropertyGroup::query()->with('items')->findOrFail($groupId);
        $payload = $request->validated('items');
        $existingIds = $group->items->pluck('id')->map(fn ($id) => (int) $id)->all();

        foreach (array_keys($payload) as $itemId) {
            if (! in_array((int) $itemId, $existingIds, true)) {
                return back()->with('error', 'Geçersiz seçenek güncellemesi.');
            }
        }

        DB::transaction(function () use ($group, $payload) {
            $defaultIds = [];

            foreach ($payload as $itemId => $data) {
                if ((bool) ($data['is_default'] ?? false)) {
                    $defaultIds[] = (int) $itemId;
                }
            }

            if ($group->type === ProductPropertyGroupType::SINGLE && count($defaultIds) > 1) {
                $keep = $defaultIds[0];
                $defaultIds = [$keep];
            }

            foreach ($payload as $itemId => $data) {
                ProductPropertyItem::query()
                    ->where('group_id', $group->id)
                    ->where('id', (int) $itemId)
                    ->update([
                        'title' => trim((string) $data['title']),
                        'price' => $data['price'],
                        'is_default' => in_array((int) $itemId, $defaultIds, true),
                        'sort_order' => $data['sort_order'] ?? 0,
                    ]);
            }
        });

        return back()->with('success', 'Seçenekler toplu olarak güncellendi.');
    }

    public function updateItem(ProductPropertyItemUpdateRequest $request, int $itemId): RedirectResponse
    {
        $item = ProductPropertyItem::query()->with('group')->findOrFail($itemId);
        $data = $request->validated();
        $isDefault = (bool) ($data['is_default'] ?? false);

        DB::transaction(function () use ($item, $data, $isDefault) {
            if ($isDefault && $item->group->type === ProductPropertyGroupType::SINGLE) {
                ProductPropertyItem::query()
                    ->where('group_id', $item->group_id)
                    ->where('id', '!=', $item->id)
                    ->update(['is_default' => false]);
            }

            $item->update([
                'title' => trim($data['title']),
                'price' => $data['price'],
                'is_default' => $isDefault,
                'sort_order' => $data['sort_order'] ?? $item->sort_order,
            ]);
        });

        return back()->with('success', 'Seçenek güncellendi.');
    }

    public function destroyItem(int $itemId): RedirectResponse
    {
        $item = ProductPropertyItem::query()->with('group')->findOrFail($itemId);
        $group = $item->group;

        if ($group->is_required && $group->items()->count() <= 1) {
            return back()->with('error', 'Zorunlu grupta en az bir seçenek kalmalıdır. Önce grubu zorunlu olmaktan çıkarın veya yeni seçenek ekleyin.');
        }

        $item->delete();

        return back()->with('success', 'Seçenek silindi.');
    }
}
