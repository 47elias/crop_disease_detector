<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = ['crop_id', 'name', 'slug', 'description', 'symptoms', 'is_healthy_class', 'model_class_index', 'severity_level'];

    protected function casts(): array
    {
        return ['is_healthy_class' => 'boolean', 'model_class_index' => 'integer'];
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function treatmentGuidelines(): HasMany
    {
        return $this->hasMany(TreatmentGuideline::class);
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function verifiedReviews(): HasMany
    {
        return $this->hasMany(ExpertReview::class, 'verified_disease_id');
    }
}
