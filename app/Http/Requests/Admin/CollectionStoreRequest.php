<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollectionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'label' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(Status::values())],
            'image' => ['required', 'image', 'max:2048'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ];
    }
}
