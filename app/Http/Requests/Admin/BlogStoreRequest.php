<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subtitle' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ImageUploadRules::adminImageRules(required: true),
        ];
    }
}
