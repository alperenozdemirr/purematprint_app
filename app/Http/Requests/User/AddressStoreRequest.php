<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\AddressScope;
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
        $scope = $this->input('scope', AddressScope::DOMESTIC->value);
        $isDomestic = $scope === AddressScope::DOMESTIC->value;

        return [
            'scope' => ['required', Rule::enum(AddressScope::class)],
            'type' => ['required', Rule::in(['home', 'work', 'other'])],
            'title' => ['required', 'string', 'max:100'],
            'custom_label' => ['nullable', 'string', 'max:100', 'required_if:type,other'],
            'content' => ['required', 'string', 'max:1000'],
            'city_id' => [
                Rule::requiredIf($isDomestic),
                'nullable',
                'integer',
                'exists:cities,id',
            ],
            'county_id' => [
                Rule::requiredIf($isDomestic),
                'nullable',
                'integer',
                'exists:counties,id',
            ],
            'country' => [
                Rule::requiredIf(! $isDomestic),
                'nullable',
                'string',
                'max:100',
            ],
            'state' => [
                Rule::requiredIf(! $isDomestic),
                'nullable',
                'string',
                'max:100',
            ],
            'city_name' => [
                Rule::requiredIf(! $isDomestic),
                'nullable',
                'string',
                'max:100',
            ],
            'postal_code' => [
                Rule::requiredIf(! $isDomestic),
                'nullable',
                'string',
                'max:32',
            ],
            'id' => ['nullable', 'integer', 'exists:addresses,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'scope' => 'adres kapsamı',
            'title' => 'adres başlığı',
            'content' => 'adres',
            'city_id' => 'il',
            'county_id' => 'ilçe',
            'country' => 'ülke',
            'state' => 'eyalet / bölge',
            'city_name' => 'şehir',
            'postal_code' => 'posta kodu',
            'custom_label' => 'adres başlığı',
        ];
    }

    public function addressAttributes(): array
    {
        $data = $this->validated();
        $scope = AddressScope::from($data['scope']);

        $attributes = [
            'scope' => $scope,
            'title' => $data['title'],
            'content' => $data['content'],
        ];

        if ($scope === AddressScope::DOMESTIC) {
            $postalCode = trim((string) ($data['postal_code'] ?? ''));

            return array_merge($attributes, [
                'city_id' => $data['city_id'],
                'county_id' => $data['county_id'],
                'country' => null,
                'state' => null,
                'city_name' => null,
                'postal_code' => $postalCode !== '' ? $postalCode : '54000',
            ]);
        }

        return array_merge($attributes, [
            'city_id' => null,
            'county_id' => null,
            'country' => $data['country'],
            'state' => $data['state'],
            'city_name' => $data['city_name'],
            'postal_code' => $data['postal_code'],
        ]);
    }
}
