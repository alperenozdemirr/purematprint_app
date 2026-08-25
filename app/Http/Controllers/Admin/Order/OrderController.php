<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Enums\OrderDesignStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderDesignUploadRequest;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Services\FileService;
use App\Http\Services\OrderFileDownloadService;
use App\Http\Services\OrderPackageCalculator;
use App\Http\Services\OrderPreparingFileService;
use App\Http\Services\ShipinkShipmentService;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        protected ShipinkShipmentService $shipinkService,
        protected OrderPackageCalculator $packageCalculator,
        protected OrderFileDownloadService $orderFileDownloadService,
        protected FileService $fileService,
        protected OrderPreparingFileService $preparingFileService,
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

        $filter = $validated['filter'] ?? null;

        if ($filter === 'awaiting_files') {
            $query->where('status', OrderStatus::PREPARING->value)
                ->whereDoesntHave('orderFiles');
        } elseif ($filter === 'awaiting_approval') {
            $query->where('design_status', OrderDesignStatus::AWAITING_APPROVAL->value);
        } elseif ($filter === 'pending_payment') {
            $query->where('status', OrderStatus::PENDING_PAYMENT->value);
        }

        $orders = $query->paginate(15)->withQueryString();
        $orderStatuses = OrderStatus::cases();
        $activeFilter = $filter;

        return view('admin.order-list', compact('orders', 'orderStatuses', 'activeFilter'));
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
                'details.properties',
                'orderFiles',
                'invoiceFile',
                'designFile',
                'designRequests.file',
                'payment',
            ])
            ->where('code', $code)
            ->firstOrFail();

        $orderStatuses = OrderStatus::cases();
        $shipinkConfigured = $this->shipinkService->isConfigured();
        $packageEstimate = $order->canCreateShipinkShipment()
            ? $this->packageCalculator->calculate($order)
            : null;
        $canManagePreparing = $order->canManageOrderFilesAndDesign();

        return view('admin.order-detail', compact(
            'order',
            'orderStatuses',
            'shipinkConfigured',
            'packageEstimate',
            'canManagePreparing',
        ));
    }

    public function uploadDesign(OrderDesignUploadRequest $request, string $code): RedirectResponse
    {
        $order = Order::query()->where('code', $code)->firstOrFail();
        $note = trim((string) ($request->validated('note') ?? ''));
        $note = $note !== '' ? $note : null;

        try {
            $this->preparingFileService->queueDesignUpload(
                $order,
                $request->file('design_file'),
                $note,
                (int) auth('admin')->id(),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('success', 'Tasarım yükleme kuyruğa alındı. İşlem bitince müşteriye e-posta gönderilecek.');
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
            'source_channel' => $validated['source_channel'] ?? $order->source_channel?->value ?? \App\Enums\OrderSourceChannel::default()->value,
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

        if ($request->hasFile('invoice_pdf')) {
            $this->fileService->uploadOrderInvoice(
                $request->file('invoice_pdf'),
                $order->id,
                auth('admin')->id(),
            );
            // PDF yüklendiyse fatura kesildi olarak işaretle
            $payload['invoice_status'] = true;
        }

        $order->update($payload);

        return redirect()->route('admin.orderDetailPage', $order->code)
            ->with('success', 'Sipariş başarıyla güncellendi.');
    }

    public function deleteInvoice(string $code): RedirectResponse
    {
        $order = Order::query()
            ->with('invoiceFile')
            ->where('code', $code)
            ->firstOrFail();

        if ($order->invoiceFile) {
            $this->fileService->imageDelete($order->invoiceFile->id, \App\Enums\ContentType::ORDER_INVOICE);
        }

        return redirect()
            ->route('admin.orderDetailPage', $order->code)
            ->with('success', 'Fatura PDF silindi.');
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
