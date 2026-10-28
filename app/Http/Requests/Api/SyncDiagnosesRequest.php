<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SyncDiagnosesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $records = $this->input('records');
        if (is_string($records)) {
            $records = json_decode($records, true);
        }
        if (is_array($records)) {
            foreach ($records as $index => &$record) {
                $record['image'] = $this->file("images.{$index}");
            }
            unset($record);
            $this->merge(['diagnoses' => $records]);
        }
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'device_model' => ['nullable', 'string', 'max:120'],
            'android_version' => ['nullable', 'string', 'max:30'],
            'ram_mb' => ['nullable', 'integer', 'min:1'],
            'app_version' => ['nullable', 'string', 'max:30'],
            'diagnoses' => ['required', 'array', 'min:1', 'max:50'],
            'diagnoses.*.local_uuid' => ['required', 'uuid'],
            'diagnoses.*.image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=224,min_height=224'],
            'diagnoses.*.disease_id' => ['required', 'integer', 'exists:diseases,id'],
            'diagnoses.*.crop_id' => ['nullable', 'integer', 'exists:crops,id'],
            'diagnoses.*.confidence' => ['required', 'numeric', 'between:0,1'],
            'diagnoses.*.top_k' => ['nullable', 'array', 'max:5'],
            'diagnoses.*.top_k.*.disease_id' => ['required', 'integer', 'exists:diseases,id'],
            'diagnoses.*.top_k.*.confidence' => ['required', 'numeric', 'between:0,1'],
            'diagnoses.*.model_version' => ['nullable', 'string', 'exists:ml_model_versions,version'],
            'diagnoses.*.inference_ms' => ['nullable', 'integer', 'min:0'],
            'diagnoses.*.ram_used_mb' => ['nullable', 'integer', 'min:0'],
            'diagnoses.*.battery_delta_percent' => ['nullable', 'numeric', 'between:0,100'],
            'diagnoses.*.captured_at' => ['nullable', 'date'],
            'diagnoses.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'diagnoses.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'diagnoses.*.capture_source' => ['nullable', 'in:camera,gallery'],
        ];
    }
}
