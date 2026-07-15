<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kategori' => $this->kategori ?: null,
            'siralama' => $this->siralama ?: null,
            'q' => trim((string) ($this->q ?? '')) ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'kategori' => ['nullable', 'string', Rule::exists('categories', 'slug')],
            'siralama' => ['nullable', Rule::in(['featured', 'price-asc', 'price-desc', 'name'])],
            'q' => ['nullable', 'string', 'max:100'],
        ];
    }
}
