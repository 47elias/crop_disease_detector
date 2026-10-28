<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosisResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'local_uuid' => $this->local_uuid,
            'status' => $this->status->value,
            'disease_id' => $this->disease_id,
            'confidence' => (float) $this->confidence,
            'synced_at' => $this->synced_at,
            'image' => [
                'url' => $this->diagnosisImage?->public_url,
                'thumbnail_url' => $this->diagnosisImage?->thumbnail_url,
            ],
        ];
    }
}
