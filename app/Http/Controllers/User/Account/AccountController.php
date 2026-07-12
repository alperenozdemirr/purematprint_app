<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AccountUpdateRequest;
use App\Http\Requests\User\AddressStoreRequest;
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
        $data = $request->validated();

        Address::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'city_id' => $data['city_id'],
            'county_id' => $data['county_id'],
        ]);

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

        $address->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'city_id' => $data['city_id'],
            'county_id' => $data['county_id'],
        ]);

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
