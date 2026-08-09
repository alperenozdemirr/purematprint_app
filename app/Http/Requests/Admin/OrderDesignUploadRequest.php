<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Services\OrderPreparingFileService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class OrderDesignUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'design_file' => [
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
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'design_file' => 'tasarım dosyası',
            'note' => 'not',
        ];
    }

    public function messages(): array
    {
        return [
            'design_file.max' => 'Dosya en fazla 200MB olabilir.',
        ];
    }
}
