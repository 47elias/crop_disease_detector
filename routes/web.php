<?php

use App\Enums\DiagnosisStatus;
use App\Models\Crop;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\MlModelVersion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index', [
        'crops' => Crop::query()->withCount('diseases')->orderBy('name')->get(),
        'diseases' => Disease::query()->with('crop')->orderBy('model_class_index')->get(),
        'healthyClass' => Disease::query()->where('is_healthy_class', true)->exists(),
        'diagnosisCount' => Diagnosis::query()->count(),
        'pendingReviewCount' => Diagnosis::query()->where('status', DiagnosisStatus::PendingReview)->count(),
        'deviceCount' => Device::query()->count(),
        'activeModel' => MlModelVersion::query()->where('is_active', true)->latest('released_at')->first(),
        'recentDiagnoses' => Diagnosis::query()
            ->with(['disease.crop', 'diagnosisImage.crop'])
            ->latest()
            ->limit(5)
            ->get(),
    ]);
})->name('dashboard');
