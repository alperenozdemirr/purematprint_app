<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:products,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_count' => 'nullable|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => ['required', Rule::in(Status::values())],
            'featured_status' => 'nullable|boolean',
            'introduction_status' => 'nullable|boolean',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ];
    }
}
