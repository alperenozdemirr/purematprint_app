<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:banners,id',
            'sub_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'redirect_url' => 'nullable|url|max:255',
            'number' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
