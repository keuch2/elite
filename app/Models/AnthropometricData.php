<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnthropometricData extends Model
{
    protected $table = 'anthropometric_data';
    
    protected $fillable = [
        'athlete_id', 'standing_height', 'sitting_height', 'wingspan',
        'weight', 'cormic_index', 'phv', 'skinfold_sum', 'fat_mass_percentage',
        'fat_mass_kg', 'muscle_mass_percentage', 'muscle_mass_kg'
    ];

    /**
     * Get the athlete evaluation this data belongs to
     */
    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
