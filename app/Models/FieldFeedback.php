<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldFeedback extends Model
{
    use HasFactory;

    protected $fillable = ['field_trial_id', 'user_id', 'usability_rating', 'readability_score', 'comments'];

    protected function casts(): array
    {
        return ['usability_rating' => 'integer', 'readability_score' => 'integer'];
    }

    public function fieldTrial(): BelongsTo
    {
        return $this->belongsTo(FieldTrial::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
