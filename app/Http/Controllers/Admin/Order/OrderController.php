<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Services\OrderFileDownloadService;
use App\Http\Services\OrderPackageCalculator;
use App\Http\Services\ShipinkShipmentService;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        protected ShipinkShipmentService $shipinkService,
        protected OrderPackageCalculator $packageCalculator,
        protected OrderFileDownloadService $orderFileDownloadService,
    ) {
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
                'orderFiles',
            ])
            ->where('code', $code)
            ->firstOrFail();

        $orderStatuses = OrderStatus::cases();
        $shipinkConfigured = $this->shipinkService->isConfigured();
        $packageEstimate = $order->canCreateShipinkShipment()
            ? $this->packageCalculator->calculate($order)
            : null;

        return view('admin.order-detail', compact('order', 'orderStatuses', 'shipinkConfigured', 'packageEstimate'));
    }

    public function downloadFile(string $code, int $fileId): StreamedResponse
    {
        $order = Order::query()
            ->where('code', $code)
            ->firstOrFail();

        return $this->orderFileDownloadService->download($order, $fileId);
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

            if ($validated['status'] === OrderStatus::CANCELLED->value && $order->status !== OrderStatus::CANCELLED) {
                if ($order->hasShipinkShipment()) {
                    $this->shipinkService->cancelShipmentForCancelledOrder($order);
                    $order->refresh();
                }
                $payload['cancelled_at'] = $order->cancelled_at ?? now();
            }

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

    public function createShipinkShipment(Request $request, string $code): RedirectResponse
    {
        $order = Order::query()
            ->with(['address', 'details.product'])
            ->where('code', $code)
            ->firstOrFail();

        $validated = $request->validate([
            'package_weight' => ['nullable', 'numeric', 'min:0.1', 'max:100'],
            'package_length' => ['nullable', 'integer', 'min:1', 'max:300'],
            'package_width' => ['nullable', 'integer', 'min:1', 'max:300'],
            'package_height' => ['nullable', 'integer', 'min:1', 'max:300'],
        ]);

        $packageOverride = [];

        if (isset($validated['package_weight'])) {
            $packageOverride['weight'] = $validated['package_weight'];
        }
        if (isset($validated['package_length'])) {
            $packageOverride['length'] = $validated['package_length'];
        }
        if (isset($validated['package_width'])) {
            $packageOverride['width'] = $validated['package_width'];
        }
        if (isset($validated['package_height'])) {
            $packageOverride['height'] = $validated['package_height'];
        }

        $result = $this->shipinkService->createShipmentForOrder(
            $order,
            $packageOverride !== [] ? $packageOverride : null
        );

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

    public function cancelOrder(string $code): RedirectResponse
    {
        $order = Order::query()
            ->with('address')
            ->where('code', $code)
            ->firstOrFail();

        if (! $order->canBeCancelledByAdmin()) {
            return redirect()
                ->route('admin.orderDetailPage', $order->code)
                ->with('error', 'Tamamlanan veya zaten iptal edilmiş siparişler iptal edilemez.');
        }

        if ($order->hasShipinkShipment()) {
            $this->shipinkService->cancelShipmentForCancelledOrder($order);
            $order->refresh();
        }

        $order->update([
            'status' => OrderStatus::CANCELLED,
            'cancelled_at' => $order->cancelled_at ?? now(),
        ]);

        return redirect()
            ->route('admin.orderDetailPage', $order->code)
            ->with('success', 'Sipariş iptal edildi.');
    }
}
