<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ProductPropertyGroupType;
use App\Enums\Status;
use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $groups = $this->input('property_groups', []);
        if (! is_array($groups)) {
            $groups = [];
        }

        $normalizedGroups = [];

        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }

            $title = trim((string) ($group['title'] ?? ''));
            $items = is_array($group['items'] ?? null) ? $group['items'] : [];
            $normalizedItems = [];

            foreach ($items as $item) {
                if (! is_array($item)) {
                    continue;
                }
                $itemTitle = trim((string) ($item['title'] ?? ''));
                if ($itemTitle === '') {
                    continue;
                }
                $normalizedItems[] = [
                    'title' => $itemTitle,
                    'price' => $item['price'] ?? 0,
                    'is_default' => filter_var($item['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sort_order' => $item['sort_order'] ?? null,
                ];
            }

            $paste = trim((string) ($group['paste'] ?? ''));
            if ($paste !== '') {
                foreach (preg_split('/\r\n|\r|\n/', $paste) ?: [] as $line) {
                    $line = trim($line);
                    if ($line === '') {
                        continue;
                    }
                    $parts = preg_split('/\s*[|;,]\s*/', $line, 2) ?: [$line];
                    $itemTitle = trim((string) ($parts[0] ?? ''));
                    if ($itemTitle === '') {
                        continue;
                    }
                    $price = isset($parts[1]) && is_numeric(str_replace(',', '.', $parts[1]))
                        ? (float) str_replace(',', '.', $parts[1])
                        : 0.0;
                    $normalizedItems[] = [
                        'title' => $itemTitle,
                        'price' => $price,
                        'is_default' => false,
                        'sort_order' => null,
                    ];
                }
            }

            // Empty untitled groups with no items are ignored
            if ($title === '' && $normalizedItems === []) {
                continue;
            }

            $normalizedGroups[] = [
                'title' => $title,
                'type' => $group['type'] ?? ProductPropertyGroupType::SINGLE->value,
                'is_required' => filter_var($group['is_required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $group['sort_order'] ?? null,
                'items' => $normalizedItems,
            ];
        }

        $this->merge(['property_groups' => $normalizedGroups]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_count' => 'nullable|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => ['required', Rule::in(Status::values())],
            'featured_status' => 'nullable|boolean',
            'introduction_status' => 'nullable|boolean',
            'description' => 'nullable|string',
            'shipping_weight' => 'nullable|numeric|min:0.001|max:100',
            'shipping_length' => 'nullable|integer|min:1|max:300',
            'shipping_width' => 'nullable|integer|min:1|max:300',
            'shipping_height' => 'nullable|integer|min:1|max:300',
            'images' => 'nullable|array',
            'images.*' => ImageUploadRules::adminImageItemRules(),
            'existing_image_order' => 'nullable|array',
            'existing_image_order.*' => 'integer|exists:files,id',
            'property_groups' => ['nullable', 'array', 'max:30'],
            'property_groups.*.title' => ['required', 'string', 'max:120'],
            'property_groups.*.type' => ['required', Rule::enum(ProductPropertyGroupType::class)],
            'property_groups.*.is_required' => ['boolean'],
            'property_groups.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'property_groups.*.items' => ['required', 'array', 'min:1', 'max:100'],
            'property_groups.*.items.*.title' => ['required', 'string', 'max:120'],
            'property_groups.*.items.*.price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'property_groups.*.items.*.is_default' => ['boolean'],
            'property_groups.*.items.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'property_groups.*.title.required' => 'Özellik grubu adı zorunludur.',
            'property_groups.*.items.required' => 'Her özellik grubuna en az bir seçenek ekleyin.',
            'property_groups.*.items.min' => 'Her özellik grubuna en az bir seçenek ekleyin.',
        ];
    }
}
