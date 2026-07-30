<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Services\ShipinkShipmentService;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected ShipinkShipmentService $shipinkService)
    {
    }

    public function index(OrderIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = Order::query()
            ->with(['user', 'address'])
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
        $shipinkConfigured = $this->shipinkService->isConfigured();

        return view('admin.order-detail', compact('order', 'orderStatuses', 'shipinkConfigured'));
    }

    public function update(OrderUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = Order::query()
            ->with('address')
            ->findOrFail($validated['id']);

        $payload = [
            'invoice_status' => $request->boolean('invoice_status'),
            'note' => $validated['note'] ?? null,
        ];

        if ($order->isInternationalShipment()) {
            $payload['status'] = $validated['status'];
            $payload['tracking_number'] = $validated['tracking_number'] ?? null;
            $payload['tracking_url'] = $validated['tracking_url'] ?? null;

            if ($validated['status'] === OrderStatus::SHIPPED->value && $order->shipped_at === null) {
                $payload['shipped_at'] = now();
            }

            if ($validated['status'] === OrderStatus::COMPLETED->value && $order->delivered_at === null) {
                $payload['delivered_at'] = now();
                $payload['shipped_at'] = $order->shipped_at ?? now();
            }
        }

        $order->update($payload);

        return redirect()->route('admin.orderDetailPage', $order->code)
            ->with('success', 'Sipariş başarıyla güncellendi.');
    }

    public function createShipinkShipment(string $code): RedirectResponse
    {
        $order = Order::query()
            ->with('address')
            ->where('code', $code)
            ->firstOrFail();

        $result = $this->shipinkService->createShipmentForOrder($order);

        return redirect()
            ->route('admin.orderDetailPage', $order->code)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function syncShipinkShipment(string $code): RedirectResponse
    {
        $order = Order::query()
            ->where('code', $code)
            ->firstOrFail();

        if (! filled($order->shipink_shipment_id)) {
            return redirect()
                ->route('admin.orderDetailPage', $order->code)
                ->with('error', 'Bu sipariş için Shipink kargo kaydı bulunamadı.');
        }

        $synced = $this->shipinkService->syncOrderShipment($order);

        return redirect()
            ->route('admin.orderDetailPage', $order->code)
            ->with($synced ? 'success' : 'error', $synced
                ? 'Kargo durumu Shipink üzerinden güncellendi.'
                : 'Kargo durumu senkronize edilemedi.');
    }

    public function cancelShipinkShipment(string $code): RedirectResponse
    {
        $order = Order::query()
            ->where('code', $code)
            ->firstOrFail();

        $result = $this->shipinkService->cancelShipmentForOrder($order);

        return redirect()
            ->route('admin.orderDetailPage', $order->code)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
