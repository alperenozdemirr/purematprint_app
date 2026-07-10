<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserIndexRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
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
}
