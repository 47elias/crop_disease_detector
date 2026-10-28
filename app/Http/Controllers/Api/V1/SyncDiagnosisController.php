<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DiagnosisStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SyncDiagnosesRequest;
use App\Http\Resources\DiagnosisResource;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\MlModelVersion;
use App\Services\DiagnosisImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SyncDiagnosisController extends Controller
{
    public function store(SyncDiagnosesRequest $request, DiagnosisImageService $images): mixed
    {
        $data = $request->validated();
        $device = Device::query()->updateOrCreate(
            ['device_uuid' => $data['device_uuid']],
            ['model' => $data['device_model'] ?? null, 'android_version' => $data['android_version'] ?? null, 'ram_mb' => $data['ram_mb'] ?? null, 'app_version' => $data['app_version'] ?? null, 'last_synced_at' => now()],
        );

        $results = DB::transaction(function () use ($data, $device, $images): array {
            $results = [];
            foreach ($data['diagnoses'] as $record) {
                $existing = Diagnosis::query()->where('local_uuid', $record['local_uuid'])->first();
                if ($existing) {
                    $results[] = ['local_uuid' => $record['local_uuid'], 'status' => 'already_synced', 'diagnosis' => new DiagnosisResource($existing)];

                    continue;
                }

                try {
                    $diagnosis = DB::transaction(function () use ($record, $device, $images): Diagnosis {
                        $image = $images->store($record['image'], [
                            'device_id' => $device->id,
                            'user_id' => auth()->id(),
                            'local_uuid' => $record['local_uuid'],
                            'crop_id' => $record['crop_id'] ?? null,
                            'captured_at' => $record['captured_at'] ?? now(),
                            'latitude' => $record['latitude'] ?? null,
                            'longitude' => $record['longitude'] ?? null,
                            'capture_source' => $record['capture_source'] ?? 'camera',
                        ]);

                        $modelVersion = isset($record['model_version'])
                            ? MlModelVersion::query()->where('version', $record['model_version'])->first()
                            : null;

                        return Diagnosis::query()->create([
                            'diagnosis_image_id' => $image->id,
                            'disease_id' => $record['disease_id'],
                            'confidence' => $record['confidence'],
                            'top_k_predictions' => $record['top_k'] ?? [],
                            'model_version_id' => $modelVersion?->id,
                            'inference_ms' => $record['inference_ms'] ?? null,
                            'ram_used_mb' => $record['ram_used_mb'] ?? null,
                            'battery_delta_percent' => $record['battery_delta_percent'] ?? null,
                            'status' => DiagnosisStatus::PendingReview,
                            'synced_at' => now(),
                            'local_uuid' => $record['local_uuid'],
                        ]);
                    });
                    $results[] = ['local_uuid' => $record['local_uuid'], 'status' => 'synced', 'diagnosis' => new DiagnosisResource($diagnosis)];
                } catch (ValidationException $exception) {
                    $results[] = ['local_uuid' => $record['local_uuid'], 'status' => 'rejected', 'errors' => $exception->errors()];
                }
            }

            return $results;
        });

        return response()->json(['data' => $results]);
    }
}
