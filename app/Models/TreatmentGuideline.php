<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentGuideline extends Model
{
    use HasFactory;

    protected $fillable = ['disease_id', 'language', 'title', 'steps', 'chemical_options', 'cultural_practices', 'sms_text'];

    protected function casts(): array
    {
        return ['steps' => 'array'];
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }
}
