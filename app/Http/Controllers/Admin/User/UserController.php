<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserIndexRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Services\UserDeletionService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RuntimeException;

class UserController extends Controller
{
    public function __construct(protected UserDeletionService $userDeletionService)
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
                $builder->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $users = $query->paginate(15)->withQueryString();
        $userTypes = \App\Enums\UserType::cases();
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
        $userTypes = \App\Enums\UserType::cases();
        $userStatuses = Status::cases();

        return view('admin.user-detail', compact('user', 'totalSpent', 'userTypes', 'userStatuses'));
    }

    public function update(UserUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->findOrFail($validated['id']);

        if ($user->id === Auth::guard('admin')->id()) {
            if ($validated['status'] === Status::PASSIVE->value) {
                return back()->with('error', 'Kendi hesabınızı pasife alamazsınız.');
            }

            if ($validated['type'] !== \App\Enums\UserType::ADMIN->value) {
                return back()->with('error', 'Kendi hesabınızın rolünü değiştiremezsiniz.');
            }
        }

        $wasActive = $user->status === Status::ACTIVE;

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'type' => $validated['type'],
            'status' => $validated['status'],
        ]);

        if ($wasActive && $user->status === Status::PASSIVE) {
            $this->userDeletionService->clearPersonalDataOnDeactivation($user->fresh());
        }

        return redirect()->route('admin.userDetailPage', $user->id)
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function deactivate(int $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        if ($user->id === Auth::guard('admin')->id()) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', 'Kendi hesabınızı pasife alamazsınız.');
        }

        if (! $this->userDeletionService->hasOrderHistory($user)) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', 'Siparişi olmayan kullanıcı pasife alınmak yerine silinebilir.');
        }

        $this->userDeletionService->deactivate($user);

        return redirect()
            ->route('admin.userDetailPage', $user->id)
            ->with('success', 'Kullanıcı pasife alındı. Sepeti temizlendi ve yorumları gizlendi; sipariş kayıtları korundu.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        if ($user->id === Auth::guard('admin')->id()) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        if ($this->userDeletionService->hasOrderHistory($user)) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', 'Bu kullanıcının sipariş geçmişi var. Silinemez; pasife alabilirsiniz.');
        }

        try {
            $this->userDeletionService->deleteFully($user);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('admin.userDetailPage', $user->id)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.userList')
            ->with('success', 'Kullanıcı ve ilişkili kişisel veriler kalıcı olarak silindi.');
    }
}
