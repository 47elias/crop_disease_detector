<?php

namespace App\Models;

use App\Enums\DispatchStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsDispatch extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosis_id', 'device_id', 'recipient_phone', 'message_body', 'status', 'sent_at', 'delivered_at', 'failure_reason'];

    protected function casts(): array
    {
        return ['status' => DispatchStatus::class, 'sent_at' => 'datetime', 'delivered_at' => 'datetime'];
    }

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
