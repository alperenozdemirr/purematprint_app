<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ProductPropertyGroupType;
use App\Models\Product;
use App\Models\ProductPropertyGroup;
use App\Models\ProductPropertyItem;
use App\Models\ShoppingCart;
use Illuminate\Validation\ValidationException;

class ProductPropertySelectionService
{
    /**
     * @param  array<int|string, mixed>  $rawSelections  group_id => item_id|item_id[]
     * @return array{
     *     item_ids: list<int>,
     *     signature: string,
     *     unit_price: float,
     *     lines: list<array{group_id:int,group_title:string,property_item_id:int,property_title:string,price:float}>
     * }
     */
    public function resolve(Product $product, array $rawSelections = []): array
    {
        $product->loadMissing(['propertyGroups.items']);

        $groups = $product->propertyGroups
            ->sortBy(['sort_order', 'id'])
            ->values();

        $normalized = $this->normalizeRawSelections($rawSelections);
        $lines = [];
        $selectedIds = [];

        foreach ($groups as $group) {
            /** @var ProductPropertyGroup $group */
            if ($group->items->isEmpty()) {
                if ($group->is_required) {
                    throw ValidationException::withMessages([
                        "properties.{$group->id}" => "\"{$group->title}\" grubu için tanımlı seçenek bulunmuyor. Lütfen yöneticiyle iletişime geçin.",
                    ]);
                }

                continue;
            }

            $chosen = $normalized[$group->id] ?? [];
            $chosen = array_values(array_unique(array_map('intval', $chosen)));

            $validIds = $group->items->pluck('id')->map(fn ($id) => (int) $id)->all();
            $invalid = array_diff($chosen, $validIds);

            if ($invalid !== []) {
                throw ValidationException::withMessages([
                    "properties.{$group->id}" => "\"{$group->title}\" için geçersiz seçim yaptınız.",
                ]);
            }

            if ($group->type === ProductPropertyGroupType::SINGLE && count($chosen) > 1) {
                throw ValidationException::withMessages([
                    "properties.{$group->id}" => "\"{$group->title}\" için yalnızca bir seçenek seçebilirsiniz.",
                ]);
            }

            if ($group->is_required && $chosen === []) {
                throw ValidationException::withMessages([
                    "properties.{$group->id}" => "\"{$group->title}\" seçimi zorunludur.",
                ]);
            }

            foreach ($chosen as $itemId) {
                /** @var ProductPropertyItem $item */
                $item = $group->items->firstWhere('id', $itemId);

                if ($item === null) {
                    continue;
                }

                $selectedIds[] = (int) $item->id;
                $lines[] = [
                    'group_id' => (int) $group->id,
                    'group_title' => (string) $group->title,
                    'property_item_id' => (int) $item->id,
                    'property_title' => (string) $item->title,
                    'price' => round((float) $item->price, 2),
                ];
            }
        }

        $submittedAll = [];
        foreach ($normalized as $ids) {
            foreach ($ids as $id) {
                $submittedAll[] = (int) $id;
            }
        }
        $unknown = array_diff($submittedAll, $selectedIds);
        if ($unknown !== []) {
            throw ValidationException::withMessages([
                'properties' => 'Seçilen özellikler bu ürüne ait değil.',
            ]);
        }

        sort($selectedIds);

        $addon = array_sum(array_column($lines, 'price'));
        $unitPrice = round((float) $product->price + $addon, 2);

        return [
            'item_ids' => $selectedIds,
            'signature' => $this->signature($selectedIds),
            'unit_price' => $unitPrice,
            'lines' => $lines,
        ];
    }

    /**
     * Re-resolve cart line from stored item IDs (live prices).
     *
     * @return array{
     *     item_ids: list<int>,
     *     signature: string,
     *     unit_price: float,
     *     lines: list<array{group_id:int,group_title:string,property_item_id:int,property_title:string,price:float}>
     * }
     */
    public function resolveFromCartItem(ShoppingCart $cartItem): array
    {
        $cartItem->loadMissing(['product.propertyGroups.items']);

        $product = $cartItem->product;
        if ($product === null) {
            throw ValidationException::withMessages([
                'cart' => 'Sepetinizdeki bir ürün artık mevcut değil.',
            ]);
        }

        $raw = $this->idsToRawSelections($product, $cartItem->selectedPropertyItemIds());

        return $this->resolve($product, $raw);
    }

    public function unitPriceForCartItem(ShoppingCart $cartItem): float
    {
        return $this->resolveFromCartItem($cartItem)['unit_price'];
    }

    /**
     * @param  list<int>  $itemIds
     */
    public function signature(array $itemIds): string
    {
        $ids = array_values(array_unique(array_map('intval', $itemIds)));
        sort($ids);

        return $ids === [] ? '' : implode('-', $ids);
    }

    /**
     * @param  array<int|string, mixed>  $rawSelections
     * @return array<int, list<int>>
     */
    public function normalizeRawSelections(array $rawSelections): array
    {
        $normalized = [];

        foreach ($rawSelections as $groupId => $value) {
            if (! is_numeric($groupId)) {
                continue;
            }

            $gid = (int) $groupId;
            if ($gid < 1) {
                continue;
            }

            if (is_array($value)) {
                $ids = array_values(array_filter(array_map('intval', $value), fn (int $id) => $id > 0));
            } elseif ($value === null || $value === '') {
                $ids = [];
            } else {
                $id = (int) $value;
                $ids = $id > 0 ? [$id] : [];
            }

            $normalized[$gid] = $ids;
        }

        return $normalized;
    }

    /**
     * Convert stored flat item IDs back into group => ids map for validation.
     *
     * @param  list<int>  $itemIds
     * @return array<int, list<int>>
     */
    public function idsToRawSelections(Product $product, array $itemIds): array
    {
        $product->loadMissing(['propertyGroups.items']);
        $raw = [];

        foreach ($product->propertyGroups as $group) {
            $raw[(int) $group->id] = [];
        }

        $itemIds = array_values(array_unique(array_map('intval', $itemIds)));

        foreach ($itemIds as $itemId) {
            foreach ($product->propertyGroups as $group) {
                if ($group->items->contains('id', $itemId)) {
                    $raw[(int) $group->id][] = $itemId;
                    break;
                }
            }
        }

        return $raw;
    }

    /**
     * Default preselected item IDs for PDP.
     *
     * @return array<int, list<int>>
     */
    public function defaultSelections(Product $product): array
    {
        $product->loadMissing(['propertyGroups.items']);
        $raw = [];

        foreach ($product->propertyGroups->sortBy(['sort_order', 'id']) as $group) {
            $defaults = $group->items->where('is_default', true)->pluck('id')->map(fn ($id) => (int) $id)->values()->all();

            if ($group->type === ProductPropertyGroupType::SINGLE) {
                $raw[(int) $group->id] = $defaults === [] ? [] : [(int) $defaults[0]];
            } else {
                $raw[(int) $group->id] = $defaults;
            }
        }

        return $raw;
    }

    /**
     * @param  Collection<int, ShoppingCart>|iterable<ShoppingCart>  $cartItems
     */
    public function assertCartStillValid(iterable $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $this->resolveFromCartItem($cartItem);
        }
    }
}
