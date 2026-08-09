<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ProductPropertyGroupType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductPropertyGroupWithItemsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->boolean('is_required'),
            'sort_order' => $this->filled('sort_order') ? (int) $this->input('sort_order') : 0,
        ]);

        $items = $this->input('items', []);
        if (! is_array($items)) {
            $items = [];
        }

        $normalized = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $normalized[] = [
                'title' => $title,
                'price' => $item['price'] ?? 0,
                'is_default' => filter_var($item['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $item['sort_order'] ?? null,
            ];
        }

        $paste = trim((string) $this->input('paste', ''));
        if ($paste !== '') {
            foreach (preg_split('/\r\n|\r|\n/', $paste) ?: [] as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                $parts = preg_split('/\s*[|;,]\s*/', $line, 2) ?: [$line];
                $title = trim((string) ($parts[0] ?? ''));
                if ($title === '') {
                    continue;
                }
                $price = isset($parts[1]) && is_numeric(str_replace(',', '.', $parts[1]))
                    ? (float) str_replace(',', '.', $parts[1])
                    : 0.0;
                $normalized[] = [
                    'title' => $title,
                    'price' => $price,
                    'is_default' => false,
                    'sort_order' => null,
                ];
            }
        }

        $this->merge(['items' => $normalized]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::enum(ProductPropertyGroupType::class)],
            'is_required' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.title' => ['required', 'string', 'max:120'],
            'items.*.price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'items.*.is_default' => ['boolean'],
            'items.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'paste' => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Grupla birlikte en az bir seçenek ekleyin.',
            'items.min' => 'Grupla birlikte en az bir seçenek ekleyin.',
        ];
    }
}
