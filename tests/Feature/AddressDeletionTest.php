<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class AddressDeletionTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_user_cannot_delete_address_linked_to_order(): void
    {
        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);
        $address = Address::query()->findOrFail($order->address_id);

        $response = $this->actingAs($user)->get(route('addressDelete', $address->id));

        $response->assertRedirect(route('addressList'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }

    public function test_user_can_delete_address_not_linked_to_order(): void
    {
        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);

        $unusedAddress = Address::query()->create([
            'user_id' => $user->id,
            'scope' => $order->address->scope,
            'title' => 'Ofis',
            'content' => 'Başka bir adres',
            'city_id' => $order->address->city_id,
            'county_id' => $order->address->county_id,
            'postal_code' => '34000',
        ]);

        $response = $this->actingAs($user)->get(route('addressDelete', $unusedAddress->id));

        $response->assertRedirect(route('addressList'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('addresses', ['id' => $unusedAddress->id]);
    }
}
