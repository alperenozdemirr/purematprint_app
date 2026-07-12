<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $type = $this->input('type');
        $title = match ($type) {
            'home' => 'Ev',
            'work' => 'İş',
            default => $this->input('custom_label') ?: 'Diğer',
        };

        $this->merge(['title' => $title]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['home', 'work', 'other'])],
            'title' => ['required', 'string', 'max:100'],
            'custom_label' => ['nullable', 'string', 'max:100', 'required_if:type,other'],
            'content' => ['required', 'string', 'max:1000'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'county_id' => ['required', 'integer', 'exists:counties,id'],
            'id' => ['nullable', 'integer', 'exists:addresses,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'adres başlığı',
            'content' => 'adres',
            'city_id' => 'il',
            'county_id' => 'ilçe',
            'custom_label' => 'adres başlığı',
        ];
    }
}
