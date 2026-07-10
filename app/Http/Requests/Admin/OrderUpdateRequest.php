<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:orders,id',
            'status' => ['required', Rule::in(OrderStatus::values())],
            'invoice_status' => 'nullable|boolean',
            'note' => 'nullable|string|max:1000',
        ];
    }
}
