<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\InvoiceType;
use App\Rules\TurkishIdentityNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoiceType = $this->input('invoice_type', InvoiceType::INDIVIDUAL->value);
        $isIndividual = $invoiceType === InvoiceType::INDIVIDUAL->value;

        return [
            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'invoice_type' => ['required', Rule::enum(InvoiceType::class)],
            'tc_no' => [
                Rule::requiredIf($isIndividual),
                'nullable',
                'digits:11',
                new TurkishIdentityNumber(),
            ],
            'company_name' => [
                Rule::requiredIf(! $isIndividual),
                'nullable',
                'string',
                'max:255',
            ],
            'tax_number' => [
                Rule::requiredIf(! $isIndividual),
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]{10,11}$/',
            ],
            'note' => ['nullable', 'string', 'max:500'],
            'order_files' => ['nullable', 'array', 'max:1'],
            'order_files.*' => [
                'file',
                'max:204800',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value instanceof \Illuminate\Http\UploadedFile) {
                        return;
                    }

                    $extension = strtolower((string) $value->getClientOriginalExtension());

                    if (! in_array($extension, \App\Http\Services\CheckoutOrderFileService::ALLOWED_EXTENSIONS, true)) {
                        $fail('Yalnızca .png, .pdf, .psd dosyaları yüklenebilir.');
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'address_id' => 'teslimat adresi',
            'invoice_type' => 'fatura tipi',
            'tc_no' => 'T.C. kimlik numarası',
            'company_name' => 'şirket adı',
            'tax_number' => 'vergi numarası',
            'note' => 'sipariş notu',
            'order_files' => 'sipariş dosyaları',
            'order_files.*' => 'sipariş dosyası',
        ];
    }

    public function messages(): array
    {
        return [
            'tax_number.regex' => 'Vergi numarası 10 veya 11 haneli olmalıdır.',
            'order_files.max' => 'En fazla 1 dosya yükleyebilirsiniz.',
            'order_files.*.max' => 'Dosya en fazla 200MB olabilir.',
            'order_files.*.file' => 'Geçerli bir dosya yükleyin.',
        ];
    }

    public function invoiceAttributes(): array
    {
        $data = $this->validated();
        $type = InvoiceType::from($data['invoice_type']);

        if ($type === InvoiceType::INDIVIDUAL) {
            return [
                'invoice_type' => $type,
                'tc_no' => $data['tc_no'],
                'company_name' => null,
                'tax_number' => null,
            ];
        }

        return [
            'invoice_type' => $type,
            'tc_no' => null,
            'company_name' => $data['company_name'],
            'tax_number' => $data['tax_number'],
        ];
    }
}
