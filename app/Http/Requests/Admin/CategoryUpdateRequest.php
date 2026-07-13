<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                Rule::notIn([$this->integer('id')]),
            ],
            'number' => 'nullable|integer|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $parentId = $this->input('parent_id');
            $categoryId = $this->integer('id');

            if (! $parentId) {
                return;
            }

            if (in_array((int) $parentId, Category::descendantIds($categoryId), true)) {
                $validator->errors()->add(
                    'parent_id',
                    'Kategori kendi alt kategorisinin altına taşınamaz.'
                );
            }
        });
    }
}
