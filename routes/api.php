<?php

use App\Http\Controllers\Api\V1\DiseaseController;
use App\Http\Controllers\Api\V1\MlModelVersionController;
use App\Http\Controllers\Api\V1\SmsDispatchController;
use App\Http\Controllers\Api\V1\SyncDiagnosisController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::post('/sync/diagnoses', [SyncDiagnosisController::class, 'store']);
    Route::get('/diseases', [DiseaseController::class, 'index']);
    Route::get('/model/latest', [MlModelVersionController::class, 'latest']);
    Route::post('/sms-dispatches', [SmsDispatchController::class, 'store']);
});
