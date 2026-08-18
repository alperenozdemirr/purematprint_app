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
        $setting = Setting::current()->load([
            'logo',
            'introImage',
            'spotlightImage',
            'bandImage',
            'teamNoteImage',
        ]);

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
        $internationalShippingMode = ShippingMode::from($validated['international_shipping_mode']);
        $freeLimitEnabled = (bool) ($validated['shipping_free_limit_enabled'] ?? false);

        $setting = Setting::current()->load([
            'logo',
            'introImage',
            'spotlightImage',
            'bandImage',
            'teamNoteImage',
        ]);

        $notificationEmails = collect($validated['order_notification_emails'] ?? [])
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->unique()
            ->take(4)
            ->values()
            ->all();

        $attributes = [
            'site_open' => (bool) ($validated['site_open'] ?? false),
            'show_real_homepage_reviews' => (bool) ($validated['show_real_homepage_reviews'] ?? false),
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
            'international_shipping_mode' => $internationalShippingMode->value,
            'international_shipping_fee' => $internationalShippingMode === ShippingMode::PAID
                ? ($validated['international_shipping_fee'] ?? 0)
                : 0,
            'shipping_first_order_free' => (bool) ($validated['shipping_first_order_free'] ?? false),
            'shipping_duration_text' => filled($validated['shipping_duration_text'] ?? null)
                ? trim((string) $validated['shipping_duration_text'])
                : null,
            'delivery_time_text' => filled($validated['delivery_time_text'] ?? null)
                ? trim((string) $validated['delivery_time_text'])
                : null,
            'email' => $validated['email'] ?? null,
            'order_notification_emails' => $notificationEmails !== [] ? $notificationEmails : null,
            'address' => $validated['address'] ?? null,
            'mobile_phone' => $validated['mobile_phone'] ?? null,
            'business_phone' => $validated['business_phone'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'whatsapp_phone' => $validated['whatsapp_phone'] ?? null,
            'short_info' => $validated['short_info'] ?? null,
            'intro_title' => filled($validated['intro_title'] ?? null)
                ? trim((string) $validated['intro_title'])
                : null,
            'intro_description' => filled($validated['intro_description'] ?? null)
                ? trim((string) $validated['intro_description'])
                : null,
            'spotlight_title' => filled($validated['spotlight_title'] ?? null)
                ? trim((string) $validated['spotlight_title'])
                : null,
            'spotlight_subtitle' => filled($validated['spotlight_subtitle'] ?? null)
                ? trim((string) $validated['spotlight_subtitle'])
                : null,
            'team_note_title' => filled($validated['team_note_title'] ?? null)
                ? trim((string) $validated['team_note_title'])
                : null,
            'team_note_description' => filled($validated['team_note_description'] ?? null)
                ? trim((string) $validated['team_note_description'])
                : null,
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

        $introImageId = $this->syncSettingImage(
            $request,
            'intro_image',
            $setting->intro_image_id,
            5,
        );
        if ($introImageId !== null) {
            $attributes['intro_image_id'] = $introImageId;
        }

        $spotlightImageId = $this->syncSettingImage(
            $request,
            'spotlight_image',
            $setting->spotlight_image_id,
            2,
        );
        if ($spotlightImageId !== null) {
            $attributes['spotlight_image_id'] = $spotlightImageId;
        }

        $bandImageId = $this->syncSettingImage(
            $request,
            'band_image',
            $setting->band_image_id,
            3,
        );
        if ($bandImageId !== null) {
            $attributes['band_image_id'] = $bandImageId;
        }

        $teamNoteImageId = $this->syncSettingImage(
            $request,
            'team_note_image',
            $setting->team_note_image_id,
            4,
        );
        if ($teamNoteImageId !== null) {
            $attributes['team_note_image_id'] = $teamNoteImageId;
        }

        Setting::saveSingleton($attributes);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Sistem ayarları güncellendi.');
    }

    private function syncSettingImage(
        SettingUpdateRequest $request,
        string $field,
        ?int $currentFileId,
        int $number,
    ): ?int {
        if (! $request->hasFile($field)) {
            return null;
        }

        if ($currentFileId) {
            $this->fileService->imageDelete($currentFileId, ContentType::OTHER);
        }

        $fileRecord = $this->fileService->imageUpload(
            $request->file($field),
            ContentType::OTHER,
            Setting::SINGLETON_ID,
            $number,
        );

        return $fileRecord->id;
    }
}
