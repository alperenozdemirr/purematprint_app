<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterBroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'subject' => 'konu',
            'content' => 'içerik',
        ];
    }
}
