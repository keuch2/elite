<?php

namespace App\Services;

class FieldMappingService
{
    /**
     * Mapeo de nombres de campos entre la base de datos (inglés) y los archivos Excel (español)
     */
    protected static $fieldMap = [
        // Datos personales
        'first_name' => 'nombre',
        'last_name' => 'apellido',
        'gender' => 'sexo',
        'birth_date' => 'fecha_de_nacimiento',
        'identity_document' => 'documento_de_identidad',
        'father_name' => 'nombre_del_padre',
        'mother_name' => 'nombre_de_la_madre',
        
        // Datos de evaluación
        'evaluation_date' => 'fecha_de_evaluacion',
        'age' => 'edad',
        'grade' => 'grado',
        'sport' => 'deporte',
        'category' => 'categoria',
        'institution' => 'institucion',
        
        // Datos antropométricos
        'standing_height' => 'talla_parado',
        'sitting_height' => 'talla_sentado',
        'wingspan' => 'envergadura',
        'weight' => 'peso',
        'cormic_index' => 'indice_cormico',
        'phv' => 'phv',
        'skinfold_sum' => 'sumatoria_de_pliegues',
        'fat_mass_percentage' => 'masa_adiposa_en_porcentaje',
        'fat_mass_kg' => 'masa_adiposa_en_kg',
        'muscle_mass_percentage' => 'masa_muscular_en_porcentaje',
        'muscle_mass_kg' => 'masa_muscular_en_kg'
    ];
    
    /**
     * Posibles variantes para cada nombre de campo en español
     * (para reconocimiento más flexible en importaciones)
     */
    protected static $possibleVariants = [
        // Datos personales
        'nombre' => ['nombre', 'first name', 'firstname', 'first_name', 'name'],
        'apellido' => ['apellido', 'last name', 'lastname', 'last_name', 'surname'],
        'sexo' => ['sexo', 'gender', 'genero', 'género'],
        'fecha_de_nacimiento' => ['fecha_de_nacimiento', 'fecha de nacimiento', 'birth date', 'birthdate', 'fecha_nacimiento', 'birth_date'],
        'fecha_de_evaluacion' => ['fecha_de_evaluacion', 'fecha de evaluacion', 'fecha evaluacion', 'evaluation date', 'evaluationdate', 'evaluation_date'],
        'edad' => ['edad', 'age'],
        'documento_de_identidad' => ['documento_de_identidad', 'documento', 'identity', 'identity_document', 'dni', 'id'],
        'nombre_del_padre' => ['nombre_del_padre', 'padre', 'father', 'father_name', 'fathers_name'],
        'nombre_de_la_madre' => ['nombre_de_la_madre', 'madre', 'mother', 'mother_name', 'mothers_name'],
        
        // Datos deportivos
        'institucion' => ['institucion', 'institución', 'institution'],
        'deporte' => ['deporte', 'sport'],
        'categoria' => ['categoria', 'categoría', 'category'],
        'grado' => ['grado', 'grade', 'curso', 'level'],
        
        // Datos antropométricos
        'talla_parado' => ['talla_parado', 'talla parado', 'standing height', 'standing_height', 'altura de pie', 'estatura'],
        'talla_sentado' => ['talla_sentado', 'talla sentado', 'sitting height', 'sitting_height', 'altura sentado'],
        'envergadura' => ['envergadura', 'wingspan', 'arm span'],
        'peso' => ['peso', 'weight'],
        'indice_cormico' => ['indice_cormico', 'índice córmico', 'indice cormico', 'cormic index', 'cormic_index'],
        'phv' => ['phv', 'peak height velocity'],
        'sumatoria_de_pliegues' => ['sumatoria_de_pliegues', 'suma de pliegues', 'skinfold sum', 'skinfold_sum'],
        'masa_adiposa_en_porcentaje' => ['masa_adiposa_en_porcentaje', 'fat mass percentage', 'fat_mass_percentage', 'porcentaje grasa'],
        'masa_adiposa_en_kg' => ['masa_adiposa_en_kg', 'fat mass kg', 'fat_mass_kg', 'masa grasa'],
        'masa_muscular_en_porcentaje' => ['masa_muscular_en_porcentaje', 'muscle mass percentage', 'muscle_mass_percentage', 'porcentaje músculo'],
        'masa_muscular_en_kg' => ['masa_muscular_en_kg', 'muscle mass kg', 'muscle_mass_kg', 'masa muscular']
    ];
    
    /**
     * Convierte un nombre de campo de inglés a español
     * 
     * @param string $englishField
     * @return string
     */
    public static function toSpanish($englishField)
    {
        return self::$fieldMap[$englishField] ?? $englishField;
    }
    
    /**
     * Convierte un nombre de campo de español a inglés
     * 
     * @param string $spanishField
     * @return string
     */
    public static function toEnglish($spanishField)
    {
        $flippedMap = array_flip(self::$fieldMap);
        return $flippedMap[$spanishField] ?? $spanishField;
    }
    
    /**
     * Normaliza un conjunto de datos, convirtiendo las claves de nombres en español a inglés
     * 
     * @param array $data
     * @return array
     */
    public static function normalizeToEnglish(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $englishKey = self::toEnglish($key);
            $result[$englishKey] = $value;
        }
        return $result;
    }
    
    /**
     * Normaliza un conjunto de datos, convirtiendo las claves de nombres en inglés a español
     * 
     * @param array $data
     * @return array
     */
    public static function normalizeToSpanish(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $spanishKey = self::toSpanish($key);
            $result[$spanishKey] = $value;
        }
        return $result;
    }
    
    /**
     * Encuentra el nombre estándar en español para un campo basado en variantes posibles
     * 
     * @param string $fieldName
     * @return string|null
     */
    public static function getStandardizedSpanishField($fieldName)
    {
        $normalizedFieldName = trim(strtolower($fieldName));
        
        foreach (self::$possibleVariants as $standardField => $variants) {
            if (in_array($normalizedFieldName, $variants)) {
                return $standardField;
            }
        }
        
        return null;
    }
    
    /**
     * Mapea un array de datos con nombres de campo posiblemente variados a nombres estandarizados
     * 
     * @param array $data
     * @return array
     */
    public static function standardizeFieldNames(array $data)
    {
        $result = [];
        
        // Normaliza las claves (trim y lowercase)
        $normalizedData = [];
        foreach ($data as $key => $value) {
            $normalizedKey = trim(strtolower($key));
            $normalizedData[$normalizedKey] = $value;
        }
        
        // Primero intenta mapear cada clave a su nombre estándar en español
        $standardizedData = [];
        foreach ($normalizedData as $key => $value) {
            $standardKey = self::getStandardizedSpanishField($key);
            if ($standardKey) {
                $standardizedData[$standardKey] = $value;
            } else {
                // Mantiene la clave original si no se encontró un mapeo
                $standardizedData[$key] = $value;
            }
        }
        
        return $standardizedData;
    }
}
