<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'scientific_name'];

    public function diseases(): HasMany
    {
        return $this->hasMany(Disease::class);
    }

    public function diagnosisImages(): HasMany
    {
        return $this->hasMany(DiagnosisImage::class);
    }

    public function diagnoses(): HasManyThrough
    {
        return $this->hasManyThrough(Diagnosis::class, DiagnosisImage::class);
    }
}
