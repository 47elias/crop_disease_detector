<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertReview extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosis_id', 'reviewer_id', 'verified_disease_id', 'is_correct', 'notes', 'reviewed_at'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean', 'reviewed_at' => 'datetime'];
    }

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function verifiedDisease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'verified_disease_id');
    }
}
