<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => $this->q ?: null,
            'type' => $this->type ?: null,
            'status' => $this->status ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',
            'type' => ['nullable', Rule::in(UserType::values())],
            'status' => ['nullable', Rule::in(Status::values())],
        ];
    }
}
