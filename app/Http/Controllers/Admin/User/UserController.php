<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserIndexRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Enums\ContentType;
use App\Http\Services\FileService;
use App\Models\Address;
use App\Models\Comment;
use App\Models\EmailVerification;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(UserIndexRequest $request): View
    {
        $validated = $request->validated();

        $query = User::query()
            ->withCount('orders')
            ->latest();

        if (! empty($validated['q'])) {
            $search = $validated['q'];
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $users = $query->paginate(15)->withQueryString();
        $userTypes = UserType::cases();
        $userStatuses = Status::cases();

        return view('admin.user-list', compact('users', 'userTypes', 'userStatuses'));
    }

    public function show(int $id): View
    {
        $user = User::query()
            ->withCount('orders')
            ->with([
                'addresses.city',
                'addresses.county',
                'orders' => fn ($query) => $query->latest()->limit(10),
            ])
            ->findOrFail($id);

        $totalSpent = $user->orders()->sum('total');
        $userTypes = UserType::cases();
        $userStatuses = Status::cases();

        return view('admin.user-detail', compact('user', 'totalSpent', 'userTypes', 'userStatuses'));
    }

    public function update(UserUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->findOrFail($validated['id']);

        if ($user->id === Auth::id()) {
            if ($validated['status'] === Status::PASSIVE->value) {
                return back()->with('error', 'Kendi hesabınızı pasife alamazsınız.');
            }

            if ($validated['type'] !== UserType::ADMIN->value) {
                return back()->with('error', 'Kendi hesabınızın rolünü değiştiremezsiniz.');
            }
        }

        $payload = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'type' => $validated['type'],
            'status' => $validated['status'],
        ];

        $user->update($payload);

        return redirect()->route('admin.userDetailPage', $user->id)
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        DB::transaction(function () use ($user) {
            $orderIds = Order::query()
                ->where('user_id', $user->id)
                ->pluck('id');

            if ($orderIds->isNotEmpty()) {
                $orderDetailIds = OrderDetail::query()
                    ->whereIn('order_id', $orderIds)
                    ->pluck('id');

                if ($orderDetailIds->isNotEmpty()) {
                    Comment::query()
                        ->whereIn('order_detail_id', $orderDetailIds)
                        ->delete();
                }

                OrderDetail::query()->whereIn('order_id', $orderIds)->delete();
                Payment::query()->whereIn('order_id', $orderIds)->delete();
                Order::query()->whereIn('id', $orderIds)->delete();
            }

            Comment::query()->where('user_id', $user->id)->delete();
            Payment::query()->where('user_id', $user->id)->delete();
            ShoppingCart::query()->where('user_id', $user->id)->delete();
            Address::query()->where('user_id', $user->id)->delete();
            EmailVerification::query()->where('email', $user->email)->delete();

            File::query()
                ->where('user_id', $user->id)
                ->pluck('id')
                ->each(fn (int $fileId) => $this->fileService->imageDelete($fileId, ContentType::USER));

            if ($user->image_id) {
                $this->fileService->imageDelete($user->image_id, ContentType::USER);
            }

            $user->tokens()->delete();
            $user->delete();
        });

        return redirect()
            ->route('admin.userList')
            ->with('success', 'Kullanıcı ve ilişkili tüm veriler silindi.');
    }
}
