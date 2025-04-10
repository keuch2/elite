<?php

use App\Models\Athlete;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $athletes = Athlete::all();
    return $athletes;
});

Route::get('/import', [ImportController::class, 'showUploadForm'])->name('import.form');
Route::post('/import', [ImportController::class, 'import'])->name('import');
Route::get('/report-config', [ReportConfigController::class, 'create'])->name('report-config.create');
Route::post('/report-config', [ReportConfigController::class, 'store'])->name('report-config.store');
Route::get('/report-config/download/{id}', [ReportConfigController::class, 'download'])->name('report-config.download');