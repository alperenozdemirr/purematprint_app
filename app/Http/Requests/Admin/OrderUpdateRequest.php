<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\OrderSourceChannel;
use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:orders,id',
            'status' => ['required', Rule::in(OrderStatus::values())],
            'source_channel' => ['nullable', Rule::in(OrderSourceChannel::values())],
            'invoice_status' => 'nullable|boolean',
            'invoice_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'note' => 'nullable|string|max:1000',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_pdf.mimes' => 'Fatura yalnızca PDF formatında yüklenebilir.',
            'invoice_pdf.max' => 'Fatura PDF en fazla 20MB olabilir.',
        ];
    }
}
