<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = Order::query()
            ->with('user')
            ->latest();

        if (! empty($validated['q'])) {
            $search = $validated['q'];
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $orders = $query->paginate(15)->withQueryString();
        $orderStatuses = OrderStatus::cases();

        return view('admin.order-list', compact('orders', 'orderStatuses'));
    }

    public function show(string $code): View
    {
        $order = Order::query()
            ->with([
                'user',
                'address.city',
                'address.county',
                'invoiceAddress.city',
                'invoiceAddress.county',
                'details.product.images',
            ])
            ->where('code', $code)
            ->firstOrFail();

        $orderStatuses = OrderStatus::cases();

        return view('admin.order-detail', compact('order', 'orderStatuses'));
    }

    public function update(OrderUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = Order::query()->findOrFail($validated['id']);

        $order->update([
            'status' => $validated['status'],
            'invoice_status' => $request->boolean('invoice_status'),
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('admin.orderDetailPage', $order->code)
            ->with('success', 'Sipariş başarıyla güncellendi.');
    }
}
