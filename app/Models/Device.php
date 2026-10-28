<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['device_uuid', 'model', 'android_version', 'ram_mb', 'app_version', 'last_synced_at'];

    protected function casts(): array
    {
        return ['ram_mb' => 'integer', 'last_synced_at' => 'datetime'];
    }

    public function diagnosisImages(): HasMany
    {
        return $this->hasMany(DiagnosisImage::class);
    }

    public function smsDispatches(): HasMany
    {
        return $this->hasMany(SmsDispatch::class);
    }
}
