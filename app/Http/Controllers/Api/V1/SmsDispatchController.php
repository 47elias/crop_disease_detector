<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DispatchStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSmsDispatchRequest;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\SmsDispatch;

class SmsDispatchController extends Controller
{
    public function store(StoreSmsDispatchRequest $request): mixed
    {
        $data = $request->validated();
        $diagnosis = Diagnosis::query()->where('local_uuid', $data['diagnosis_local_uuid'])->firstOrFail();
        $device = isset($data['device_uuid']) ? Device::query()->where('device_uuid', $data['device_uuid'])->first() : null;
        $dispatch = SmsDispatch::query()->create([
            'diagnosis_id' => $diagnosis->id,
            'device_id' => $device?->id,
            'recipient_phone' => $data['recipient_phone'],
            'message_body' => $data['message_body'],
            'status' => DispatchStatus::from($data['status']),
            'sent_at' => $data['sent_at'] ?? null,
            'delivered_at' => $data['delivered_at'] ?? null,
            'failure_reason' => $data['failure_reason'] ?? null,
        ]);

        return response()->json(['data' => $dispatch], 201);
    }
}
