<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminSearchService
{
    /**
     * @return array{q: string, orders: list<array<string, mixed>>, customers: list<array<string, mixed>>, products: list<array<string, mixed>>}
     */
    public function search(string $query, int $limit = 6): array
    {
        $q = trim($query);

        if (mb_strlen($q) < 2) {
            return [
                'q' => $q,
                'orders' => [],
                'customers' => [],
                'products' => [],
            ];
        }

        $like = '%'.$q.'%';

        $orders = Order::query()
            ->with(['user:id,name,email,phone'])
            ->where(function ($builder) use ($like) {
                $builder->where('code', 'like', $like)
                    ->orWhereHas('user', function ($userQuery) use ($like) {
                        $userQuery->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like)
                            ->orWhere('phone', 'like', $like);
                    })
                    ->orWhereHas('details.product', function ($productQuery) use ($like) {
                        $productQuery->where('title', 'like', $like)
                            ->orWhere('code', 'like', $like);
                    });
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (Order $order) => [
                'type' => 'order',
                'label' => $order->code,
                'meta' => trim(($order->user?->name ?? '—').' · '.($order->status?->label() ?? '')),
                'url' => route('admin.orderDetailPage', $order->code),
            ]);

        $customers = User::query()
            ->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (User $user) => [
                'type' => 'customer',
                'label' => $user->name,
                'meta' => trim(($user->email ?? '').($user->phone ? ' · '.$user->phone : '')),
                'url' => route('admin.userDetailPage', $user->id),
            ]);

        $products = Product::query()
            ->where(function ($builder) use ($like) {
                $builder->where('title', 'like', $like)
                    ->orWhere('code', 'like', $like);
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => [
                'type' => 'product',
                'label' => $product->title,
                'meta' => $product->code,
                'url' => route('admin.productEditPage', $product->slug),
            ]);

        return [
            'q' => $q,
            'orders' => $orders->values()->all(),
            'customers' => $customers->values()->all(),
            'products' => $products->values()->all(),
        ];
    }
}
