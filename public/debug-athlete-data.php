<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load Laravel bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get report ID from query parameter
$reportId = $_GET['id'] ?? 2;

// Get the report with related data
$report = App\Models\Report::with(['athlete', 'athlete.athleteProfile', 'athlete.institution'])->find($reportId);

if (!$report) {
    echo "<h1>Error: Informe no encontrado</h1>";
    exit;
}

echo "<h1>Diagnóstico de Datos del Informe #{$report->id}</h1>";

// Check if athlete relation exists
echo "<h2>Información del Atleta</h2>";
if (!$report->athlete) {
    echo "<p style='color: red;'>Error: El informe no tiene un atleta asociado</p>";
} else {
    echo "<p>ID del Atleta: {$report->athlete->id}</p>";
    echo "<p>ID del Perfil de Atleta: " . ($report->athlete->athlete_profile_id ?? 'NULL') . "</p>";
    echo "<p>Fecha de Evaluación: " . ($report->athlete->evaluation_date ?? 'No disponible') . "</p>";
    echo "<p>Edad: " . ($report->athlete->age ?? 'No disponible') . "</p>";
    echo "<p>Deporte: " . ($report->athlete->sport ?? 'No disponible') . "</p>";
    echo "<p>Grado: " . ($report->athlete->grade ?? 'No disponible') . "</p>";

    // Check if athlete profile relation exists
    echo "<h3>Perfil del Atleta</h3>";
    if (!$report->athlete->athleteProfile) {
        echo "<p style='color: red;'>Error: El atleta no tiene un perfil asociado</p>";
    } else {
        echo "<p>Nombre: " . $report->athlete->athleteProfile->first_name . " " . $report->athlete->athleteProfile->last_name . "</p>";
        echo "<p>Género: " . $report->athlete->athleteProfile->gender . "</p>";
        echo "<p>Fecha de Nacimiento: " . $report->athlete->athleteProfile->birth_date . "</p>";
        echo "<p>Documento de Identidad: " . ($report->athlete->athleteProfile->identity_document ?? 'No disponible') . "</p>";
    }

    // Check if institution relation exists
    echo "<h3>Institución</h3>";
    if (!$report->athlete->institution) {
        echo "<p style='color: red;'>Error: El atleta no tiene una institución asociada</p>";
    } else {
        echo "<p>Nombre de Institución: " . $report->athlete->institution->name . "</p>";
    }
}

// Check report data
echo "<h2>Datos del Informe</h2>";
if (empty($report->report_data)) {
    echo "<p style='color: red;'>Error: El informe no tiene datos</p>";
} else {
    echo "<h3>Datos almacenados en el informe:</h3>";
    echo "<pre>" . print_r($report->report_data, true) . "</pre>";
}

// Debug database queries
echo "<h2>Estructura de la Base de Datos</h2>";

// Check athletes table
echo "<h3>Estructura de la tabla 'athletes'</h3>";
$columns = DB::select("SHOW COLUMNS FROM athletes");
echo "<pre>" . print_r($columns, true) . "</pre>";

// Check athlete_profiles table
echo "<h3>Estructura de la tabla 'athlete_profiles'</h3>";
$columns = DB::select("SHOW COLUMNS FROM athlete_profiles");
echo "<pre>" . print_r($columns, true) . "</pre>";

// Check the specific athlete record
if ($report->athlete) {
    echo "<h3>Registro completo del atleta ID: {$report->athlete->id}</h3>";
    $athleteRecord = DB::select("SELECT * FROM athletes WHERE id = ?", [$report->athlete->id]);
    echo "<pre>" . print_r($athleteRecord, true) . "</pre>";
    
    if ($report->athlete->athlete_profile_id) {
        echo "<h3>Registro completo del perfil de atleta ID: {$report->athlete->athlete_profile_id}</h3>";
        $profileRecord = DB::select("SELECT * FROM athlete_profiles WHERE id = ?", [$report->athlete->athlete_profile_id]);
        echo "<pre>" . print_r($profileRecord, true) . "</pre>";
    }
}
?>
