<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\Status;
use App\Http\Services\UserDeletionService;
use App\Models\Comment;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class UserDeletionServiceTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_deactivate_hides_comments_and_clears_cart(): void
    {
        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);
        $detail = $order->details->first();

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $detail->product_id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        Comment::query()->create([
            'product_id' => $detail->product_id,
            'user_id' => $user->id,
            'order_detail_id' => $detail->id,
            'content' => 'Harika ürün',
            'rating' => 5,
            'is_visible' => true,
        ]);

        app(UserDeletionService::class)->deactivate($user->fresh());

        $user->refresh();
        $this->assertSame(Status::PASSIVE, $user->status);
        $this->assertDatabaseMissing('shopping_carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'is_visible' => false,
        ]);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    }

    public function test_delete_fully_blocks_when_orders_exist(): void
    {
        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);

        $this->expectException(RuntimeException::class);

        app(UserDeletionService::class)->deleteFully($user);
    }

    public function test_delete_fully_removes_user_without_orders(): void
    {
        $user = User::factory()->create([
            'phone' => '5321112233',
            'status' => Status::ACTIVE,
        ]);

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $this->createDomesticOrder()->details->first()->product_id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        app(UserDeletionService::class)->deleteFully($user);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('shopping_carts', ['user_id' => $user->id]);
    }
}
