<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldTrial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ward', 'starts_at', 'ends_at', 'description'];

    protected function casts(): array
    {
        return ['starts_at' => 'date', 'ends_at' => 'date'];
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(FieldFeedback::class);
    }
}
