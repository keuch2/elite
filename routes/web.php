<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportConfigController;
use Illuminate\Http\Request;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\InstitutionController;

// Debug route to check route methods
Route::match(['get', 'post', 'head'], '/', function (Request $request) {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/report-templates', [ReportConfigController::class, 'create'])
        ->name('report-config.create');
    Route::post('/report-templates', [ReportConfigController::class, 'store'])
        ->name('report-config.store');
    Route::get('/report-templates/{config}/download', [ReportConfigController::class, 'download'])
        ->name('report-config.download');

    Route::get('/import', [ImportController::class, 'showUploadForm'])->name('import.form');
    Route::post('/import', [ImportController::class, 'import'])->name('import.file');
    Route::post('/generate-reports', [ImportController::class, 'generateReports'])->name('generate.reports');

    // Athlete routes
    Route::resource('athletes', AthleteController::class);
    Route::get('athletes/{id}/evaluation', [AthleteController::class, 'showEvaluation'])->name('athletes.evaluation');
    Route::get('athletes/{id}/add-evaluation', [AthleteController::class, 'addEvaluation'])->name('athletes.add-evaluation');
    Route::post('athletes/{id}/store-evaluation', [AthleteController::class, 'storeEvaluation'])->name('athletes.store-evaluation');
    Route::get('athletes/compare', [AthleteController::class, 'compareEvaluations'])->name('athletes.compare');

    // Institution routes
    Route::resource('institutions', InstitutionController::class);
    Route::get('institutions/{id}/evaluations', [InstitutionController::class, 'evaluations'])->name('institutions.evaluations');
    Route::get('institutions/{id}/reports', [InstitutionController::class, 'reports'])->name('institutions.reports');
});

// Debugging route information
Route::get('/routes', function () {
    $routes = Route::getRoutes();
    return view('routes', ['routes' => $routes]);
});

require __DIR__.'/auth.php';

