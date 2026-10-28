<?php

namespace App\Enums;

enum DiagnosisStatus: string
{
    case PendingReview = 'pending_review';
    case Confirmed = 'confirmed';
    case Corrected = 'corrected';
    case Rejected = 'rejected';
}
