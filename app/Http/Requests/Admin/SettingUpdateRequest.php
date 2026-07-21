<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
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
        ]);
    }

    public function rules(): array
    {
        return [
            'site_open' => ['boolean'],
            'discount_enabled' => ['boolean'],
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
        ];
    }

    public function messages(): array
    {
        return [
            'discount_value.max' => 'Yüzdelik indirim en fazla 100 olabilir.',
        ];
    }
}
