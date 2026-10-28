<?php

namespace App\Models;

use App\Enums\CaptureSource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DiagnosisImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['device_id', 'user_id', 'local_uuid', 'file_path', 'thumbnail_path', 'original_filename', 'mime_type', 'size_bytes', 'width', 'height', 'sha256', 'captured_at', 'latitude', 'longitude', 'crop_id', 'capture_source', 'is_training_candidate'];

    protected function casts(): array
    {
        return ['captured_at' => 'datetime', 'latitude' => 'decimal:7', 'longitude' => 'decimal:7', 'is_training_candidate' => 'boolean', 'capture_source' => CaptureSource::class];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function diagnosis(): HasOne
    {
        return $this->hasOne(Diagnosis::class);
    }

    public function scopeUnsynced(Builder $query): Builder
    {
        return $query->whereDoesntHave('diagnosis', fn (Builder $q) => $q->whereNotNull('synced_at'));
    }

    public function scopeForDisease(Builder $query, int $diseaseId): Builder
    {
        return $query->whereHas('diagnosis', fn (Builder $q) => $q->where('disease_id', $diseaseId));
    }

    public function scopeBetweenDates(Builder $query, mixed $from, mixed $to): Builder
    {
        return $query->whereBetween('captured_at', [$from, $to]);
    }

    public function getPublicUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::disk(config('filesystems.default'))->url($this->file_path) : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::disk(config('filesystems.default'))->url($this->thumbnail_path) : null;
    }
}
