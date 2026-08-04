<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class BannerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'redirect_url' => ['nullable', 'string', 'max:255'],
            'btn_title' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'integer', 'min:0'],
            'image' => ImageUploadRules::adminImageRules(required: true),
        ];
    }
}
