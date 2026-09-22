<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\OrderDesignType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
            'properties' => ['nullable', 'array'],
            'properties.*' => ['nullable'],
            'after_action' => ['nullable', 'in:cart,checkout'],
            'design_type' => ['nullable', Rule::in(OrderDesignType::checkoutOptionValues())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantity' => $this->quantity ?: 1,
        ]);
    }
}
