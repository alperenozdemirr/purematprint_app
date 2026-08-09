<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = AdminNotification::query()
            ->with('order:id,code')
            ->latest()
            ->paginate(30);

        $visibleIds = $notifications->getCollection()->pluck('id')->all();
        $this->markIdsAsRead($visibleIds);

        $notifications->setCollection(
            $notifications->getCollection()->map(function (AdminNotification $notification) {
                if ($notification->read_at === null) {
                    $notification->read_at = now();
                }

                return $notification;
            })
        );

        return view('admin.notification-list', [
            'notifications' => $notifications,
            'adminUnreadNotificationCount' => AdminNotification::query()->unread()->count(),
        ]);
    }

    public function recent(): JsonResponse
    {
        $items = AdminNotification::query()
            ->with('order:id,code')
            ->latest()
            ->limit(10)
            ->get();

        $this->markIdsAsRead($items->pluck('id')->all());

        $payload = $items->map(function (AdminNotification $notification) {
            if ($notification->read_at === null) {
                $notification->read_at = now();
            }

            return $this->serialize($notification);
        });

        return response()->json([
            'unread_count' => AdminNotification::query()->unread()->count(),
            'items' => $payload,
        ]);
    }

    public function markViewed(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['ids'] ?? [])));
        $this->markIdsAsRead($ids);

        return response()->json([
            'ok' => true,
            'unread_count' => AdminNotification::query()->unread()->count(),
        ]);
    }

    /**
     * @param  list<int>  $ids
     */
    private function markIdsAsRead(array $ids): void
    {
        if ($ids === []) {
            return;
        }

        AdminNotification::query()
            ->whereIn('id', $ids)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function open(int $id): RedirectResponse
    {
        $notification = AdminNotification::query()->with('order:id,code')->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->url();

        if ($url === null) {
            return redirect()
                ->route('admin.notificationList')
                ->with('error', 'Bu bildirime bağlı sipariş bulunamadı.');
        }

        return redirect()->to($url);
    }

    public function markRead(int $id): JsonResponse|RedirectResponse
    {
        $notification = AdminNotification::query()->findOrFail($id);
        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'unread_count' => AdminNotification::query()->unread()->count()]);
        }

        return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
    }

    public function markAllRead(): JsonResponse|RedirectResponse
    {
        AdminNotification::query()->unread()->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'unread_count' => 0]);
        }

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        AdminNotification::query()->whereKey($id)->delete();

        return back()->with('success', 'Bildirim silindi.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:notifications,id'],
        ]);

        AdminNotification::query()->whereIn('id', $validated['ids'])->delete();

        return back()->with('success', count($validated['ids']).' bildirim silindi.');
    }

    public function destroyAll(): RedirectResponse
    {
        AdminNotification::query()->delete();

        return back()->with('success', 'Tüm bildirimler silindi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(AdminNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type?->value,
            'type_label' => $notification->type?->label(),
            'title' => $notification->title,
            'body' => $notification->body,
            'is_read' => $notification->isRead(),
            'url' => route('admin.notificationOpen', $notification->id),
            'created_at' => $notification->created_at?->diffForHumans(),
        ];
    }
}
