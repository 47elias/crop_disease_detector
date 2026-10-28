<?php

namespace App\Models;

use App\Enums\DiagnosisStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosis_image_id', 'disease_id', 'confidence', 'top_k_predictions', 'model_version_id', 'inference_ms', 'ram_used_mb', 'battery_delta_percent', 'status', 'synced_at', 'local_uuid'];

    protected function casts(): array
    {
        return ['confidence' => 'decimal:4', 'top_k_predictions' => 'array', 'battery_delta_percent' => 'decimal:2', 'status' => DiagnosisStatus::class, 'synced_at' => 'datetime'];
    }

    public function diagnosisImage(): BelongsTo
    {
        return $this->belongsTo(DiagnosisImage::class);
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function modelVersion(): BelongsTo
    {
        return $this->belongsTo(MlModelVersion::class, 'model_version_id');
    }

    public function expertReviews(): HasMany
    {
        return $this->hasMany(ExpertReview::class);
    }

    public function smsDispatches(): HasMany
    {
        return $this->hasMany(SmsDispatch::class);
    }

    public function scopeUnsynced(Builder $query): Builder
    {
        return $query->whereNull('synced_at');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', DiagnosisStatus::Confirmed);
    }

    public function scopeLowConfidence(Builder $query, float $threshold = 0.6): Builder
    {
        return $query->where('confidence', '<', $threshold);
    }

    public function scopeForDisease(Builder $query, int $diseaseId): Builder
    {
        return $query->where('disease_id', $diseaseId);
    }

    public function scopeBetweenDates(Builder $query, mixed $from, mixed $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
