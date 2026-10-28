<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmsDispatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diagnosis_local_uuid' => ['required', 'uuid', 'exists:diagnoses,local_uuid'],
            'device_uuid' => ['nullable', 'uuid', 'exists:devices,device_uuid'],
            'recipient_phone' => ['required', 'string', 'max:30'],
            'message_body' => ['required', 'string', 'max:160'],
            'status' => ['required', 'in:queued,sent,delivered,failed'],
            'sent_at' => ['nullable', 'date'],
            'delivered_at' => ['nullable', 'date'],
            'failure_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
