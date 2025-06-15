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

// Get the report
$report = App\Models\Report::with(['athlete', 'athlete.athleteProfile'])->find($reportId);

if (!$report) {
    echo "<h1>Report not found</h1>";
    exit;
}

echo "<h1>Report Data Debug</h1>";
echo "<h2>Report ID: {$report->id}</h2>";

// Show report data
echo "<h3>Raw Report Data:</h3>";
echo "<pre>";
print_r($report->report_data);
echo "</pre>";

// Check for radar fields
echo "<h3>Radar Fields Check:</h3>";
$radarFieldsMap = [
    'sit_and_reach' => 'Sit & Reach',
    'sentadilla_arranque' => 'Sentadilla Arranque',
    'fuerza_agarre' => 'Fuerza de Agarre',
    'potencia_piernas' => 'Potencia de Piernas',
    'potencia_brazos' => 'Potencia de Brazos',
    'velocidad_10m' => 'Velocidad 10m',
    'velocidad_20m' => 'Velocidad 20m',
    'velocidad_30m' => 'Velocidad 30m',
    'course_navette' => 'Course Navette',
    'vo2_max' => 'VO2 Max',
    'abalakov_altura_cm' => 'Abalakov Altura',
    'cmj_altura_cm' => 'CMJ Altura',
    'reaccion_oculo_manual' => 'Reacción Óculo Manual',
    'reaccion_oculo_podal' => 'Reacción Óculo Podal'
];

// Check selected radar fields
$selectedRadarFields = $report->report_data['radar_fields'] ?? [];
echo "Selected radar fields: ";
if (empty($selectedRadarFields)) {
    echo "None";
} else {
    echo "<ul>";
    foreach ($selectedRadarFields as $field) {
        echo "<li>$field</li>";
    }
    echo "</ul>";
}

// Check for valid numeric fields
echo "<h3>Available Numeric Fields:</h3>";
$numericFields = [];
foreach ($report->report_data as $key => $value) {
    if (is_numeric($value) && isset($radarFieldsMap[$key])) {
        $numericFields[$key] = $value;
    }
}

if (empty($numericFields)) {
    echo "No numeric fields found that match radar field keys.";
} else {
    echo "<ul>";
    foreach ($numericFields as $key => $value) {
        echo "<li>$key: $value</li>";
    }
    echo "</ul>";
}

// Check if there are enough data points
$hasEnoughData = count($numericFields) >= 3;
echo "<h3>Has Enough Data for Radar Chart: " . ($hasEnoughData ? "YES" : "NO") . "</h3>";
echo "Needs at least 3 numeric fields, found " . count($numericFields);

// Show a simple radar chart if there are enough data points
if ($hasEnoughData) {
    echo '<h3>Test Radar Chart</h3>';
    echo '<div style="width:400px;height:400px;"><canvas id="testRadarChart"></canvas></div>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    var ctx = document.getElementById("testRadarChart").getContext("2d");';
    echo '    var radarLabels = [];';
    echo '    var radarData = [];';
    
    foreach ($numericFields as $key => $value) {
        echo '    radarLabels.push("' . ($radarFieldsMap[$key] ?? $key) . '");';
        echo '    radarData.push(' . $value . ');';
    }
    
    echo '    var radarChart = new Chart(ctx, {';
    echo '        type: "radar",';
    echo '        data: {';
    echo '            labels: radarLabels,';
    echo '            datasets: [{';
    echo '                label: "Resultados del atleta",';
    echo '                data: radarData,';
    echo '                backgroundColor: "rgba(54, 162, 235, 0.2)",';
    echo '                borderColor: "rgb(54, 162, 235)",';
    echo '                borderWidth: 3';
    echo '            }]';
    echo '        },';
    echo '        options: {';
    echo '            scales: {';
    echo '                r: {';
    echo '                    angleLines: { display: true },';
    echo '                    suggestedMin: 0';
    echo '                }';
    echo '            }';
    echo '        }';
    echo '    });';
    echo '});';
    echo '</script>';
}
?>
