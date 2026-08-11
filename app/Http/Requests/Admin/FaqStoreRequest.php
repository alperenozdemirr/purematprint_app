<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:faq_groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'number' => ['nullable', 'integer', 'min:0'],
            'fixed_status' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'fixed_status' => $this->boolean('fixed_status'),
        ]);
    }
}
