<?php

namespace App\Services;

use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\ExpertReview;
use App\Models\SmsDispatch;
use Illuminate\Support\Collection;

class DiagnosisAnalyticsService
{
    /** @return array{true_positive: int, false_positive: int, false_negative: int, true_negative: int, accuracy: float, precision: float, recall: float, f1_score: float} */
    public function metricsForDisease(Disease $disease): array
    {
        $reviews = ExpertReview::query()->with('diagnosis')->whereNotNull('verified_disease_id')->get();
        $truePositive = $reviews->filter(fn (ExpertReview $review): bool => $review->verified_disease_id === $disease->id && $review->diagnosis?->disease_id === $disease->id)->count();
        $falseNegative = $reviews->filter(fn (ExpertReview $review): bool => $review->verified_disease_id === $disease->id && $review->diagnosis?->disease_id !== $disease->id)->count();
        $falsePositive = $reviews->filter(fn (ExpertReview $review): bool => $review->verified_disease_id !== $disease->id && $review->diagnosis?->disease_id === $disease->id)->count();
        $trueNegative = $reviews->filter(fn (ExpertReview $review): bool => $review->verified_disease_id !== $disease->id && $review->diagnosis?->disease_id !== $disease->id)->count();
        $total = $truePositive + $falsePositive + $falseNegative + $trueNegative;
        $precision = $truePositive + $falsePositive > 0 ? $truePositive / ($truePositive + $falsePositive) : 0.0;
        $recall = $truePositive + $falseNegative > 0 ? $truePositive / ($truePositive + $falseNegative) : 0.0;

        return [
            'true_positive' => $truePositive,
            'false_positive' => $falsePositive,
            'false_negative' => $falseNegative,
            'true_negative' => $trueNegative,
            'accuracy' => $total > 0 ? ($truePositive + $trueNegative) / $total : 0.0,
            'precision' => $precision,
            'recall' => $recall,
            'f1_score' => $precision + $recall > 0 ? 2 * $precision * $recall / ($precision + $recall) : 0.0,
        ];
    }

    public function averageInferenceMs(): ?float
    {
        $average = Diagnosis::query()->whereNotNull('inference_ms')->avg('inference_ms');

        return $average === null ? null : (float) $average;
    }

    public function smsDeliverySuccessRate(): float
    {
        $total = SmsDispatch::query()->count();

        return $total === 0 ? 0.0 : SmsDispatch::query()->where('status', 'delivered')->count() / $total;
    }

    /** @return Collection<int, object> */
    public function diseaseCountsPerWard(): Collection
    {
        return Diagnosis::query()
            ->join('diagnosis_images', 'diagnoses.diagnosis_image_id', '=', 'diagnosis_images.id')
            ->join('users', 'diagnosis_images.user_id', '=', 'users.id')
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->selectRaw('users.ward, diseases.id as disease_id, diseases.name as disease_name, COUNT(*) as diagnosis_count')
            ->groupBy('users.ward', 'diseases.id', 'diseases.name')
            ->get();
    }
}
