<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class MlModelVersion extends Model
{
    use HasFactory;

    protected $fillable = ['version', 'file_path', 'size_mb', 'quantisation', 'accuracy', 'precision', 'recall', 'f1_score', 'is_active', 'released_at'];

    protected function casts(): array
    {
        return ['size_mb' => 'decimal:2', 'accuracy' => 'decimal:4', 'precision' => 'decimal:4', 'recall' => 'decimal:4', 'f1_score' => 'decimal:4', 'is_active' => 'boolean', 'released_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if ($model->quantisation === 'int8' && (float) $model->size_mb >= 15) {
                throw ValidationException::withMessages(['size_mb' => 'An INT8 production model must be smaller than 15 MB.']);
            }
        });
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class, 'model_version_id');
    }
}
