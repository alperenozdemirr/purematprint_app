<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'site_open' => $this->boolean('site_open'),
            'discount_enabled' => $this->boolean('discount_enabled'),
            'shipping_free_limit_enabled' => $this->boolean('shipping_free_limit_enabled'),
            'shipping_first_order_free' => $this->boolean('shipping_first_order_free'),
            'show_real_homepage_reviews' => $this->boolean('show_real_homepage_reviews'),
        ]);
    }

    public function rules(): array
    {
        return [
            'site_open' => ['boolean'],
            'discount_enabled' => ['boolean'],
            'show_real_homepage_reviews' => ['boolean'],
            'discount_type' => ['nullable', Rule::enum(DiscountType::class), 'required_if:discount_enabled,1'],
            'discount_value' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:discount_enabled,1',
                Rule::when(
                    $this->input('discount_type') === DiscountType::PERCENT->value,
                    ['max:100']
                ),
            ],
            'discount_scope' => ['required', Rule::enum(DiscountScope::class)],
            'shipping_mode' => ['required', Rule::enum(ShippingMode::class)],
            'shipping_fee' => ['nullable', 'numeric', 'min:0', 'required_if:shipping_mode,paid'],
            'shipping_free_limit_enabled' => ['boolean'],
            'shipping_free_limit' => ['nullable', 'numeric', 'min:0', 'required_if:shipping_free_limit_enabled,1'],
            'international_shipping_mode' => ['required', Rule::enum(ShippingMode::class)],
            'international_shipping_fee' => ['nullable', 'numeric', 'min:0', 'required_if:international_shipping_mode,paid'],
            'shipping_first_order_free' => ['boolean'],
            'shipping_duration_text' => ['nullable', 'string', 'max:120'],
            'delivery_time_text' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'order_notification_emails' => ['nullable', 'array', 'max:4'],
            'order_notification_emails.*' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'business_phone' => ['nullable', 'string', 'max:50'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_phone' => ['nullable', 'string', 'max:50'],
            'short_info' => ['nullable', 'string', 'max:500'],
            'logo' => ImageUploadRules::adminImageRules(required: false),
            'intro_title' => ['nullable', 'string', 'max:255'],
            'intro_description' => ['nullable', 'string', 'max:1000'],
            'intro_image' => ImageUploadRules::introImageRules(required: false),
            'spotlight_title' => ['nullable', 'string', 'max:255'],
            'spotlight_subtitle' => ['nullable', 'string', 'max:120'],
            'spotlight_image' => ImageUploadRules::adminImageRules(required: false),
            'band_image' => ImageUploadRules::adminImageRules(required: false),
            'team_note_title' => ['nullable', 'string', 'max:255'],
            'team_note_description' => ['nullable', 'string', 'max:2000'],
            'team_note_image' => ImageUploadRules::adminImageRules(required: false),
        ];
    }

    public function messages(): array
    {
        return [
            'discount_value.max' => 'Yüzdelik indirim en fazla 100 olabilir.',
            'order_notification_emails.max' => 'En fazla 4 bildirim e-posta adresi girebilirsiniz.',
            'logo.max' => 'Logo en fazla 40MB olabilir.',
            'logo.mimes' => 'Logo yalnızca '.ImageUploadRules::humanList().' formatlarında olabilir.',
            'intro_image.max' => 'Giriş görseli en fazla 100MB olabilir.',
            'intro_image.mimes' => 'Giriş görseli yalnızca '.ImageUploadRules::humanList().' formatlarında olabilir.',
            'spotlight_image.max' => 'Spotlight görseli en fazla 40MB olabilir.',
            'spotlight_image.mimes' => 'Spotlight görseli yalnızca '.ImageUploadRules::humanList().' formatlarında olabilir.',
            'band_image.max' => 'Atölye band görseli en fazla 40MB olabilir.',
            'band_image.mimes' => 'Atölye band görseli yalnızca '.ImageUploadRules::humanList().' formatlarında olabilir.',
            'team_note_image.max' => 'Ekip notu görseli en fazla 40MB olabilir.',
            'team_note_image.mimes' => 'Ekip notu görseli yalnızca '.ImageUploadRules::humanList().' formatlarında olabilir.',
        ];
    }
}
