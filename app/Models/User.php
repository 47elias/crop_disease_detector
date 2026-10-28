<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'ward', 'district', 'role', 'preferred_language'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function diagnosisImages(): HasMany
    {
        return $this->hasMany(DiagnosisImage::class);
    }

    public function expertReviews(): HasMany
    {
        return $this->hasMany(ExpertReview::class, 'reviewer_id');
    }

    public function diagnoses(): HasManyThrough
    {
        return $this->hasManyThrough(Diagnosis::class, DiagnosisImage::class);
    }

    public function fieldFeedback(): HasMany
    {
        return $this->hasMany(FieldFeedback::class);
    }
}
