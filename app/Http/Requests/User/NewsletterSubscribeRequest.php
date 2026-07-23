<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsletterSubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('newsletters', 'email'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Bu e-posta adresi bülten listesine zaten kayıtlı.',
        ];
    }
}
