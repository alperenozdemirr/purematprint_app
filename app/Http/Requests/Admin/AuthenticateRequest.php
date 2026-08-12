<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;

class AuthenticateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
            'cf-turnstile-response' => [new Turnstile()],
        ];
    }

    public function messages(): array
    {
        return [
            'cf-turnstile-response.required' => 'Güvenlik doğrulamasını tamamlayın.',
        ];
    }
}
