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
            // 1. Check if we have the minimum required data
            if (empty($row['nombre']) || empty($row['apellido']) || 
                empty($row['sexo']) || empty($row['fecha_de_nacimiento']) ||
                empty($row['fecha_de_evaluacion']) || empty($row['edad'])) {
                throw new \Exception("Missing required fields: first name, last name, gender, birth date, evaluation date and age are required");
            }
            
            // 2. Check if this athlete already exists (by identity document or name + birth date)
            $identityDocument = $row['documento_de_identidad'] ?? null;
            $firstName = $row['nombre'];
            $lastName = $row['apellido'];
            $birthDate = $this->transformDate($row['fecha_de_nacimiento']);
            
            $existingAthlete = null;
            
            if ($identityDocument) {
                $existingAthlete = Athlete::where('identity_document', $identityDocument)->first();
            } else {
                $existingAthlete = Athlete::where('first_name', $firstName)
                                        ->where('last_name', $lastName)
                                        ->where('birth_date', $birthDate)
                                        ->first();
            }

            // 3. Create a new athlete record for a new evaluation
            $athlete = new Athlete();
            
            if ($existingAthlete) {
                // Copy the profile data from the existing athlete
                $athlete->first_name = $existingAthlete->first_name;
                $athlete->last_name = $existingAthlete->last_name;
                $athlete->gender = $existingAthlete->gender;
                $athlete->identity_document = $existingAthlete->identity_document;
                $athlete->birth_date = $existingAthlete->birth_date;
                $athlete->father_name = $existingAthlete->father_name ?? $row['nombre_del_padre'] ?? null;
                $athlete->mother_name = $existingAthlete->mother_name ?? $row['nombre_de_la_madre'] ?? null;
                $athlete->tutor_id = $existingAthlete->tutor_id;
            } else {
                // Set up the new athlete profile data
                $athlete->first_name = $firstName;
                $athlete->last_name = $lastName;
                $athlete->gender = $row['sexo'];
                $athlete->identity_document = $identityDocument;
                $athlete->birth_date = $birthDate;
                $athlete->father_name = $row['nombre_del_padre'] ?? null;
                $athlete->mother_name = $row['nombre_de_la_madre'] ?? null;
            }

            // Set evaluation-specific data
            $athlete->evaluation_date = $this->transformDate($row['fecha_de_evaluacion']);
            $athlete->age = $row['edad'];
            $athlete->grade = $row['grado'] ?? null;
            $athlete->sport = $row['deporte'] ?? null;
            $athlete->category = $row['categoria'] ?? null;
            
            // Save the new athlete record (which represents a new evaluation)
            $athlete->save();

            // 4. Create anthropometric data for this evaluation
            $anthropometricData = [
                'standing_height' => $row['talla_parado'] ?? null,
                'sitting_height' => $row['talla_sentado'] ?? null,
                'wingspan' => $row['envergadura'] ?? null,
                'weight' => $row['peso'] ?? null,
                'cormic_index' => $row['indice_cormico'] ?? null,
                'phv' => $row['phv'] ?? null,
                'skinfold_sum' => $row['sumatoria_de_pliegues'] ?? null,
                'fat_mass_percentage' => $row['masa_adiposa_en_porcentaje'] ?? null,
                'fat_mass_kg' => $row['masa_adiposa_en_kg'] ?? null,
                'muscle_mass_percentage' => $row['masa_muscular_en_porcentaje'] ?? null,
                'muscle_mass_kg' => $row['masa_muscular_en_kg'] ?? null,
            ];

            // Only create anthropometric data if at least one field has a value
            if (array_filter($anthropometricData)) {
                AnthropometricData::create(
                    array_merge(['athlete_id' => $athlete->id], $anthropometricData)
                );
            }

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