<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Institution;
use App\Models\AnthropometricData;
use App\Services\FieldMappingService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class AthletesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Start a transaction to ensure data consistency
        return DB::transaction(function () use ($row) {
            // Log the headers we received to see what's available
            \Illuminate\Support\Facades\Log::info('Excel row data:', $row);
            
            // Estandarizar los nombres de campo utilizando el servicio de mapeo
            $standardRow = FieldMappingService::standardizeFieldNames($row);
            
            // Check for missing required fields and collect specific messages
            $missingFields = [];
            $requiredFields = ['nombre', 'apellido', 'sexo', 'fecha_de_nacimiento', 'fecha_de_evaluacion', 'edad'];
            
            foreach ($requiredFields as $field) {
                if (empty($standardRow[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                throw new \Exception("Campos requeridos faltantes: " . implode(', ', $missingFields) . 
                                    ". Por favor, verifique los nombres de columnas en su archivo Excel. Columnas disponibles: " . 
                                    implode(', ', array_keys($row)));
            }
            
            // 2. Check if this athlete already exists (by identity document or name + birth date)
            $identityDocument = $standardRow['documento_de_identidad'] ?? null;
            
            // Fix possible confusion between first name and last name
            // If nombre contains a space and apellido doesn't, they might be swapped
            $firstName = trim($standardRow['nombre']);
            $lastName = trim($standardRow['apellido']);
            
            // Detect if names might be swapped based on common patterns
            if (strpos($firstName, ' ') !== false && strpos($lastName, ' ') === false) {
                // The first name has a space and the last name doesn't - might be swapped
                // This is just a heuristic - could be improved based on data patterns
                $temp = $firstName;
                $firstName = $lastName;
                $lastName = $temp;
            }
            
            $birthDate = $this->transformDate($standardRow['fecha_de_nacimiento']);
            
            $existingAthlete = null;
            
            if ($identityDocument) {
                $existingProfile = \App\Models\AthleteProfile::where('identity_document', $identityDocument)->first();
                if ($existingProfile) {
                    $existingAthlete = Athlete::where('athlete_profile_id', $existingProfile->id)->first();
                }
            } else {
                $existingProfile = \App\Models\AthleteProfile::where('first_name', $firstName)
                                        ->where('last_name', $lastName)
                                        ->where('birth_date', $birthDate)
                                        ->first();
                if ($existingProfile) {
                    $existingAthlete = Athlete::where('athlete_profile_id', $existingProfile->id)->first();
                }
            }

            // 3. Create or update athlete profile first
            if ($existingProfile) {
                // Update existing profile with any new data
                if (isset($row['nombre_del_padre']) && !$existingProfile->father_name) {
                    $existingProfile->father_name = $row['nombre_del_padre'];
                }
                if (isset($row['nombre_de_la_madre']) && !$existingProfile->mother_name) {
                    $existingProfile->mother_name = $row['nombre_de_la_madre'];
                }
                // Only save if changes were made
                if ($existingProfile->isDirty()) {
                    $existingProfile->save();
                }
                $profileId = $existingProfile->id;
            } else {
                // Create new athlete profile
                $profile = new \App\Models\AthleteProfile();
                $profile->first_name = $firstName;
                $profile->last_name = $lastName;
                $profile->gender = $this->transformGender($standardRow['sexo']);
                $profile->identity_document = $identityDocument;
                $profile->birth_date = $birthDate;
                $profile->father_name = $standardRow['nombre_del_padre'] ?? null;
                $profile->mother_name = $standardRow['nombre_de_la_madre'] ?? null;
                $profile->save();
                $profileId = $profile->id;
            }
            
            // Process institution if provided
            $institutionId = null;
            if (!empty($standardRow['institucion'])) {
                // Try to find the institution by name
                $institution = Institution::where('name', 'like', '%' . trim($standardRow['institucion']) . '%')->first();
                
                // If institution doesn't exist, create it
                if (!$institution) {
                    $institution = new Institution();
                    $institution->name = trim($standardRow['institucion']);
                    $institution->save();
                    
                    \Illuminate\Support\Facades\Log::info('Nueva institución creada: ' . $institution->name);
                }
                
                $institutionId = $institution->id;
                
                // Update athlete profile with institution if needed
                if ($existingProfile && !$existingProfile->institution_id) {
                    $existingProfile->institution_id = $institutionId;
                    $existingProfile->save();
                    
                    \Illuminate\Support\Facades\Log::info('Perfil de atleta actualizado con institución ID: ' . $institutionId);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('No se encontró información de institución en la fila importada');
                \Illuminate\Support\Facades\Log::info('Campos disponibles: ' . implode(', ', array_keys($standardRow)));
            }
            
            // 4. Create a new athlete record (evaluation) linked to the profile
            $athlete = new Athlete();
            $athlete->athlete_profile_id = $profileId;
            
            // Generate a unique evaluation_id (UUID)
            $athlete->evaluation_id = \Illuminate\Support\Str::uuid()->toString();

            // Set evaluation-specific data
            $athlete->evaluation_date = $this->transformDate($standardRow['fecha_de_evaluacion']);
            $athlete->age = $standardRow['edad'];
            $athlete->grade = $standardRow['grado'] ?? null;
            $athlete->sport = $standardRow['deporte'] ?? null;
            $athlete->category = $standardRow['categoria'] ?? null;
            
            // Set the institution if we found or created one
            if ($institutionId) {
                $athlete->institution_id = $institutionId;
                \Illuminate\Support\Facades\Log::info('Asignando institución ID ' . $institutionId . ' a la evaluación');
            }
            
            // Save the new athlete record (which represents a new evaluation)
            $athlete->save();
            \Illuminate\Support\Facades\Log::info('Evaluación de atleta guardada con ID: ' . $athlete->id);

            // 4. Create anthropometric data for this evaluation
            $anthropometricData = [
                'standing_height' => $standardRow['talla_parado'] ?? null,
                'sitting_height' => $standardRow['talla_sentado'] ?? null,
                'wingspan' => $standardRow['envergadura'] ?? null,
                'weight' => $standardRow['peso'] ?? null,
                'cormic_index' => $standardRow['indice_cormico'] ?? null,
                'phv' => $standardRow['phv'] ?? null,
                'skinfold_sum' => $standardRow['sumatoria_de_pliegues'] ?? null,
                'fat_mass_percentage' => $standardRow['masa_adiposa_en_porcentaje'] ?? null,
                'fat_mass_kg' => $standardRow['masa_adiposa_en_kg'] ?? null,
                'muscle_mass_percentage' => $standardRow['masa_muscular_en_porcentaje'] ?? null,
                'muscle_mass_kg' => $standardRow['masa_muscular_en_kg'] ?? null,
            ];
            
            \Illuminate\Support\Facades\Log::info('Datos antropométricos a guardar: ', array_filter($anthropometricData));

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
    
    // Helper function to transform gender values to database enum format
    private function transformGender($gender)
    {
        if (!$gender) return null;
        
        $gender = strtolower(trim($gender));
        
        if (in_array($gender, ['f', 'm', 'other'])) {
            return $gender;
        }
        
        // Spanish gender terms
        if (in_array($gender, ['femenino', 'mujer', 'f', 'female'])) {
            return 'f';
        }
        
        if (in_array($gender, ['masculino', 'hombre', 'm', 'male'])) {
            return 'm';
        }
        
        // Default to other if unknown
        return 'other';
    }
}