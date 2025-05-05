<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportConfigController;
use Illuminate\Http\Request;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MailoutController;

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
    Route::get('/report-templates/{config}/edit', [ReportConfigController::class, 'edit'])
        ->name('report-config.edit');
    Route::put('/report-templates/{config}', [ReportConfigController::class, 'update'])
        ->name('report-config.update');
    Route::delete('/report-templates/{config}', [ReportConfigController::class, 'destroy'])
        ->name('report-config.destroy');

    // Import routes
    Route::get('/import', [ImportController::class, 'showUploadForm'])->name('import.form');
    Route::post('/import', [ImportController::class, 'import'])->name('import.file');
    Route::post('/generate-reports', [ImportController::class, 'generateReports'])->name('generate.reports');

    Route::get('/reports/{report}/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf');
    Route::get('/reports/{report}', [ReportController::class, 'show'])
        ->name('reports.show');
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store');

    // Report routes - order is important for wildcard routes
    Route::get('/reports/athletes-by-institution/{institution_id}', [ReportController::class, 'getAthletesByInstitution'])
        ->name('reports.athletes-by-institution');
    Route::get('/reports/athlete-details/{athlete_id}', [ReportController::class, 'getAthleteDetails'])
        ->name('reports.athlete-details');
    Route::get('/reports/athlete/{athlete}', [ReportController::class, 'athleteReports'])
        ->name('reports.athlete');
    Route::get('/reports/compare/{athlete}', [ReportController::class, 'compareReports'])
        ->name('reports.compare');
    Route::get('/reports/create', [ReportController::class, 'create'])
        ->name('reports.create');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])
        ->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])
        ->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])
        ->name('reports.destroy');
    Route::get('/reports/{report}/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf');
    Route::get('/reports/{report}', [ReportController::class, 'show'])
        ->name('reports.show');
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store');

    // Mailout routes
    Route::get('/mailouts', [MailoutController::class, 'index'])->name('mailouts.index');
    Route::get('/mailouts/athletes/{institution_id}', [MailoutController::class, 'getAthletesByInstitution'])->name('mailouts.athletes');
    Route::post('/mailouts/send', [MailoutController::class, 'send'])->name('mailouts.send');

    // Athlete routes - order is important to avoid route conflicts
    Route::get('athletes/compare', [AthleteController::class, 'compareEvaluations'])
        ->name('athletes.compare');
    Route::get('athletes/{id}/evaluation', [AthleteController::class, 'showEvaluation'])
        ->where('id', '[0-9]+')
        ->name('athletes.evaluation');
    Route::resource('athletes', AthleteController::class);

    // Institution routes
    Route::get('institutions/{id}/evaluations', [InstitutionController::class, 'reports'])
        ->name('institutions.evaluations');
    Route::get('institutions/{id}/reports', [InstitutionController::class, 'reports'])
        ->name('institutions.reports');
    Route::resource('institutions', InstitutionController::class);
});

// Public report routes (accessible via signed URL)
Route::get('/public/report/{report}', [MailoutController::class, 'publicReport'])->name('public.report');
Route::get('/public/report/{report}/pdf', [ReportController::class, 'exportPdf'])->name('public.report.pdf');

// Debugging route information
Route::get('/routes', function () {
    $routes = Route::getRoutes();
    return view('routes', ['routes' => $routes]);
});

require __DIR__.'/auth.php';
