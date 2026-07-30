<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ShipinkSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'shipink_warehouse_id' => ['nullable', 'uuid'],
            'shipink_warehouse_name' => ['nullable', 'string', 'max:255'],
            'shipink_carrier_account_id' => ['nullable', 'uuid'],
            'shipink_carrier_account_label' => ['nullable', 'string', 'max:255'],
            'shipink_carrier_service_id' => ['nullable', 'string', 'max:100'],
            'shipink_card_id' => ['nullable', 'uuid'],
            'shipink_card_label' => ['nullable', 'string', 'max:255'],
            'shipink_default_weight' => ['required', 'integer', 'min:1', 'max:100'],
            'shipink_default_length' => ['required', 'integer', 'min:1', 'max:300'],
            'shipink_default_width' => ['required', 'integer', 'min:1', 'max:300'],
            'shipink_default_height' => ['required', 'integer', 'min:1', 'max:300'],
            'shipink_sender_name' => ['nullable', 'string', 'max:255'],
            'shipink_sender_person_name' => ['nullable', 'string', 'max:255'],
            'shipink_sender_company_name' => ['nullable', 'string', 'max:255'],
            'shipink_sender_tax_id' => ['nullable', 'string', 'max:20'],
            'shipink_sender_tax_office' => ['nullable', 'string', 'max:255'],
            'shipink_sender_phone' => ['nullable', 'string', 'max:20'],
            'shipink_sender_email' => ['nullable', 'email', 'max:255'],
            'shipink_sender_street' => ['nullable', 'string', 'max:500'],
            'shipink_sender_city' => ['nullable', 'string', 'max:100'],
            'shipink_sender_state' => ['nullable', 'string', 'max:100'],
            'shipink_sender_zip' => ['nullable', 'string', 'max:20'],
        ];
    }
}
