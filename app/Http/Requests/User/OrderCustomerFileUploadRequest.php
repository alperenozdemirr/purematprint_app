<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Services\OrderPreparingFileService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class OrderCustomerFileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:204800',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value instanceof UploadedFile) {
                        return;
                    }

                    $extension = strtolower((string) $value->getClientOriginalExtension());

                    if (! in_array($extension, OrderPreparingFileService::ALLOWED_EXTENSIONS, true)) {
                        $fail('Yalnızca .png, .pdf, .psd, .jpg, .jpeg dosyaları yüklenebilir.');
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'sipariş dosyası',
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'Dosya en fazla 200MB olabilir.',
        ];
    }
}
