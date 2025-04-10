<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\AnthropometricData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class AthletesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Start a transaction to ensure data consistency
        return DB::transaction(function () use ($row) {
            // Create or update the athlete based on identity_document
            $athlete = Athlete::updateOrCreate(
                ['identity_document' => $row['documento_de_identidad']],
                [
                    'first_name' => $row['nombre'],
                    'last_name' => $row['apellido'],
                    'gender' => $row['sexo'],
                    'birth_date' => $this->transformDate($row['fecha_de_nacimiento']),
                    'evaluation_date' => $this->transformDate($row['fecha_de_evaluacion']),
                    'age' => $row['edad'],
                    'grade' => $row['grado'],
                    'sport' => $row['deporte'],
                    'category' => $row['categoria'],
                    'father_name' => $row['nombre_del_padre'],
                    'mother_name' => $row['nombre_de_la_madre'],
                    // Tutor and institution can be linked later via separate logic if needed
                ]
            );

            // Add anthropometric data for the athlete
            AnthropometricData::updateOrCreate(
                ['athlete_id' => $athlete->id],
                [
                    'standing_height' => $row['talla_parado'],
                    'sitting_height' => $row['talla_sentado'],
                    'wingspan' => $row['envergadura'],
                    'weight' => $row['peso'],
                    'cormic_index' => $row['indice_cormico'],
                    'phv' => $row['phv'],
                    'skinfold_sum' => $row['sumatoria_de_pliegues'],
                    'fat_mass_percentage' => $row['masa_adiposa_en_porcentaje'],
                    'fat_mass_kg' => $row['masa_adiposa_en_kg'],
                    'muscle_mass_percentage' => $row['masa_muscular_en_porcentaje'],
                    'muscle_mass_kg' => $row['masa_muscular_en_kg'],
                ]
            );

            return $athlete;
        });
    }

    // Helper to convert Excel date strings to YYYY-MM-DD format
    private function transformDate($value)
    {
        if (!$value) return null;
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
    }

    // Define the heading row (assumes row 2 has headers)
    public function headingRow(): int
    {
        return 2;
    }
}