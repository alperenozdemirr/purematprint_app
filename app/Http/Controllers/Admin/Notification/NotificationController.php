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

        return view('admin.notification-list', compact('notifications'));
    }

    public function recent(): JsonResponse
    {
        $items = AdminNotification::query()
            ->with('order:id,code')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (AdminNotification $n) => $this->serialize($n));

        $unreadCount = AdminNotification::query()->unread()->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'items' => $items,
        ]);
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
