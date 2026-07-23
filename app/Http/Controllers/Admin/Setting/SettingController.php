<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Setting;

use App\Enums\ContentType;
use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Http\Services\FileService;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function edit(): View
    {
        $setting = Setting::current()->load('logo');

        return view('admin.settings', [
            'setting' => $setting,
            'discountTypes' => DiscountType::cases(),
            'discountScopes' => DiscountScope::cases(),
            'shippingModes' => ShippingMode::cases(),
        ]);
    }

    public function update(SettingUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $discountEnabled = (bool) ($validated['discount_enabled'] ?? false);
        $shippingMode = ShippingMode::from($validated['shipping_mode']);
        $freeLimitEnabled = (bool) ($validated['shipping_free_limit_enabled'] ?? false);

        $setting = Setting::current()->load('logo');

        $attributes = [
            'site_open' => (bool) ($validated['site_open'] ?? false),
            'discount_enabled' => $discountEnabled,
            'discount_type' => $discountEnabled ? $validated['discount_type'] : null,
            'discount_value' => $discountEnabled ? $validated['discount_value'] : null,
            'discount_scope' => $validated['discount_scope'],
            'shipping_mode' => $shippingMode->value,
            'shipping_fee' => $shippingMode === ShippingMode::PAID ? ($validated['shipping_fee'] ?? 0) : 0,
            'shipping_free_limit_enabled' => $shippingMode === ShippingMode::PAID && $freeLimitEnabled,
            'shipping_free_limit' => $shippingMode === ShippingMode::PAID && $freeLimitEnabled
                ? ($validated['shipping_free_limit'] ?? null)
                : null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'mobile_phone' => $validated['mobile_phone'] ?? null,
            'business_phone' => $validated['business_phone'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'whatsapp_phone' => $validated['whatsapp_phone'] ?? null,
            'short_info' => $validated['short_info'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            if ($setting->logo_id) {
                $this->fileService->imageDelete($setting->logo_id, ContentType::OTHER);
            }

            $fileRecord = $this->fileService->imageUpload(
                $request->file('logo'),
                ContentType::OTHER,
                Setting::SINGLETON_ID,
                1
            );

            $attributes['logo_id'] = $fileRecord->id;
        }

        Setting::saveSingleton($attributes);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Sistem ayarları güncellendi.');
    }
}
