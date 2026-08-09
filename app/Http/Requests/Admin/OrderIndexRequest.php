<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => $this->q ?: null,
            'status' => $this->status ?: null,
            'filter' => $this->filter ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(OrderStatus::values())],
            'filter' => ['nullable', Rule::in([
                'awaiting_files',
                'awaiting_approval',
                'pending_payment',
            ])],
        ];
    }
}
