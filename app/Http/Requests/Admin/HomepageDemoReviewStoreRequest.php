<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class HomepageDemoReviewStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quote' => ['required', 'string', 'max:2000'],
            'author' => ['required', 'string', 'max:160'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'is_visible' => ['nullable', 'boolean'],
            'image' => ImageUploadRules::introImageRules(required: false),
        ];
    }
}
