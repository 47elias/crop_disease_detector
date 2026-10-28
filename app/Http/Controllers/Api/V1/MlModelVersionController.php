<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MlModelVersion;
use Illuminate\Support\Facades\Storage;

class MlModelVersionController extends Controller
{
    public function latest(): mixed
    {
        $model = MlModelVersion::query()->where('is_active', true)->latest('released_at')->first();
        if (! $model) {
            return response()->json(['message' => 'No active model is available.'], 404);
        }

        return response()->json(['data' => [
            'version' => $model->version,
            'size_mb' => (float) $model->size_mb,
            'quantisation' => $model->quantisation,
            'accuracy' => $model->accuracy,
            'released_at' => $model->released_at,
            'download_url' => $model->file_path ? Storage::disk(config('filesystems.default'))->url($model->file_path) : null,
        ]]);
    }
}
