<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ProductPropertyGroupType;
use App\Enums\Status;
use App\Http\Services\ProductPropertySelectionService;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPropertyGroup;
use App\Models\ProductPropertyItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductPropertySelectionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductPropertySelectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProductPropertySelectionService::class);
    }

    public function test_resolves_unit_price_with_single_and_multiple_selections(): void
    {
        $product = $this->makeProduct(100);
        $size = $this->makeGroup($product, 'Ölçü', ProductPropertyGroupType::SINGLE, true);
        $extra = $this->makeGroup($product, 'Ek', ProductPropertyGroupType::MULTIPLE, false);

        $sizeA = $this->makeItem($size, '50x50', 0);
        $sizeB = $this->makeItem($size, '100x100', 200);
        $lam = $this->makeItem($extra, 'Laminasyon', 50);
        $uv = $this->makeItem($extra, 'UV', 120);

        $resolved = $this->service->resolve($product->fresh(['propertyGroups.items']), [
            $size->id => $sizeB->id,
            $extra->id => [$lam->id, $uv->id],
        ]);

        $this->assertSame(470.0, $resolved['unit_price']);
        $this->assertCount(3, $resolved['lines']);
        $this->assertSame($this->service->signature([$sizeB->id, $lam->id, $uv->id]), $resolved['signature']);
        $this->assertSame($sizeA->id, $sizeA->id); // keep unused var quiet for phpstan-less env
    }

    public function test_required_single_group_must_be_selected(): void
    {
        $product = $this->makeProduct(100);
        $size = $this->makeGroup($product, 'Ölçü', ProductPropertyGroupType::SINGLE, true);
        $this->makeItem($size, '50x50', 0);

        $this->expectException(ValidationException::class);
        $this->service->resolve($product->fresh(['propertyGroups.items']), []);
    }

    public function test_rejects_foreign_item_ids(): void
    {
        $productA = $this->makeProduct(100);
        $productB = $this->makeProduct(200);
        $groupA = $this->makeGroup($productA, 'Ölçü', ProductPropertyGroupType::SINGLE, false);
        $groupB = $this->makeGroup($productB, 'Ölçü', ProductPropertyGroupType::SINGLE, false);
        $this->makeItem($groupA, 'A', 0);
        $itemB = $this->makeItem($groupB, 'B', 10);

        $this->expectException(ValidationException::class);
        $this->service->resolve($productA->fresh(['propertyGroups.items']), [
            $groupA->id => $itemB->id,
        ]);
    }

    private function makeProduct(float $price): Product
    {
        $category = Category::query()->create([
            'name' => 'Test Cat '.uniqid(),
            'slug' => 'test-cat-'.uniqid(),
            'status' => Status::ACTIVE,
            'number' => 1,
        ]);

        return Product::query()->create([
            'title' => 'Test Product '.uniqid(),
            'slug' => 'test-product-'.uniqid(),
            'code' => 'P'.random_int(10000, 99999),
            'price' => $price,
            'stock_count' => 10,
            'category_id' => $category->id,
            'status' => Status::ACTIVE,
            'featured_status' => false,
            'introduction_status' => false,
        ]);
    }

    private function makeGroup(
        Product $product,
        string $title,
        ProductPropertyGroupType $type,
        bool $required,
    ): ProductPropertyGroup {
        return ProductPropertyGroup::query()->create([
            'product_id' => $product->id,
            'title' => $title,
            'type' => $type,
            'is_required' => $required,
            'sort_order' => 1,
        ]);
    }

    private function makeItem(ProductPropertyGroup $group, string $title, float $price): ProductPropertyItem
    {
        return ProductPropertyItem::query()->create([
            'group_id' => $group->id,
            'title' => $title,
            'price' => $price,
            'is_default' => false,
            'sort_order' => 1,
        ]);
    }
}
