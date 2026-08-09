<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductPropertyItemBulkUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);

        if (! is_array($items)) {
            $items = [];
        }

        $normalized = [];

        foreach ($items as $id => $item) {
            if (! is_array($item) || ! is_numeric($id)) {
                continue;
            }

            $normalized[(int) $id] = [
                'title' => trim((string) ($item['title'] ?? '')),
                'price' => $item['price'] ?? 0,
                'is_default' => filter_var($item['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $item['sort_order'] ?? 0,
            ];
        }

        $this->merge(['items' => $normalized]);
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.title' => ['required', 'string', 'max:120'],
            'items.*.price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'items.*.is_default' => ['boolean'],
            'items.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
