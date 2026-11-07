<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tracking_code' => [
                'required',
                'string',
                'max:64',
                Rule::unique('shipments', 'tracking_code'),
            ],
            'pickup_address_id' => ['required', 'integer', 'exists:addresses,id'],
            'delivery_address_id' => ['required', 'integer', 'exists:addresses,id'],
            'status' => ['nullable', 'string', 'max:32'],
            'service_level' => ['nullable', 'string', 'max:32'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'pieces' => ['nullable', 'integer', 'min:1'],
            'cod_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
