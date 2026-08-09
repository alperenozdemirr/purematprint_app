<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderDesignDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['approve', 'revise'])],
            'note' => [
                Rule::requiredIf(fn () => $this->input('decision') === 'revise'),
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'decision' => 'karar',
            'note' => 'not',
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => 'Revize talebi için lütfen bir not yazın.',
        ];
    }
}
