<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiagnosisStatus;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\MlModelVersion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $maizePredicate = static fn ($query) => $query->whereHas('crop', fn ($cropQuery) => $cropQuery->where('name', 'maize'))->orWhere('is_healthy_class', true);

        return view('dashboard.index', [
            'diseases' => Disease::query()->where($maizePredicate)->with('crop')->orderBy('model_class_index')->get(),
            'diagnosisCount' => Diagnosis::query()->whereHas('disease', $maizePredicate)->count(),
            'pendingReviewCount' => Diagnosis::query()->where('status', DiagnosisStatus::PendingReview)->whereHas('disease', $maizePredicate)->count(),
            'deviceCount' => Device::query()->count(),
            'activeModel' => MlModelVersion::query()->where('is_active', true)->latest('released_at')->first(),
            'recentDiagnoses' => Diagnosis::query()->with(['disease.crop', 'diagnosisImage.crop'])->whereHas('disease', $maizePredicate)->latest()->limit(5)->get(),
        ]);
    }
}
