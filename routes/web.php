<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::controller(ModuleController::class)->group(function (): void {
    Route::get('/diseases', fn () => app(ModuleController::class)->index('diseases'))->name('diseases.index');
    Route::get('/diagnoses', fn () => app(ModuleController::class)->index('diagnoses'))->name('diagnoses.index');
    Route::get('/farmers', fn () => app(ModuleController::class)->index('farmers'))->name('farmers.index');
    Route::get('/devices', fn () => app(ModuleController::class)->index('devices'))->name('devices.index');
    Route::get('/treatment-guidelines', fn () => app(ModuleController::class)->index('treatments'))->name('treatments.index');
    Route::get('/expert-reviews', fn () => app(ModuleController::class)->index('reviews'))->name('reviews.index');
    Route::get('/models', fn () => app(ModuleController::class)->index('models'))->name('models.index');
    Route::get('/sms-dispatches', fn () => app(ModuleController::class)->index('sms'))->name('sms.index');
    Route::get('/field-trials', fn () => app(ModuleController::class)->index('field-trials'))->name('field-trials.index');
    Route::get('/field-feedback', fn () => app(ModuleController::class)->index('field-feedback'))->name('field-feedback.index');
});
