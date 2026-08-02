<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AccountUpdateRequest;
use App\Http\Requests\User\AddressStoreRequest;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Http\Services\QueuedMailService;
use App\Mail\PasswordChangedMail;
use App\Models\Address;
use App\Models\City;
use App\Models\County;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('user.default.account', [
            'user' => auth()->user(),
            'activeNav' => 'account',
        ]);
    }

    public function update(AccountUpdateRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return back()->with('success', 'Profil bilgileriniz güncellendi.');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => $request->validated('password'),
        ]);

        try {
            app(QueuedMailService::class)->queue($user->email, new PasswordChangedMail(
                $user,
                now()->timezone(config('app.timezone'))->format('d.m.Y H:i'),
            ));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return back()->with('success', 'Şifreniz güncellendi. Güvenlik bildirimi e-posta adresinize gönderildi.');
    }

    public function addressList(): View
    {
        $addresses = Address::query()
            ->with(['city', 'county'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.default.address', [
            'addresses' => $addresses,
            'activeNav' => 'addresses',
        ]);
    }

    public function addressCreatePage(): View
    {
        return view('user.default.new-address', [
            'address' => null,
            'cities' => City::query()->orderBy('name')->get(),
            'counties' => County::query()->orderBy('name')->get(),
            'activeNav' => 'addresses',
        ]);
    }

    public function addressEditPage(int $id): View
    {
        $address = Address::query()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.default.new-address', [
            'address' => $address,
            'cities' => City::query()->orderBy('name')->get(),
            'counties' => County::query()->orderBy('name')->get(),
            'activeNav' => 'addresses',
        ]);
    }

    public function addressStore(AddressStoreRequest $request): RedirectResponse
    {
        Address::create(array_merge(
            ['user_id' => auth()->id()],
            $request->addressAttributes(),
        ));

        return redirect()
            ->route('addressList')
            ->with('success', 'Adres kaydedildi.');
    }

    public function addressUpdate(AddressStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $address = Address::query()
            ->where('user_id', auth()->id())
            ->findOrFail($data['id']);

        $address->update($request->addressAttributes());

        return redirect()
            ->route('addressList')
            ->with('success', 'Adres güncellendi.');
    }

    public function addressDestroy(int $id): RedirectResponse
    {
        Address::query()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('addressList')
            ->with('success', 'Adres silindi.');
    }
}
