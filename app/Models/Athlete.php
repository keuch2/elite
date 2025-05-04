<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model {
    
    protected $table = 'athletes'; // Use the athletes table we just created
    
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'identity_document',
        'birth_date', 'father_name', 'mother_name', 'evaluation_date',
        'age', 'grade', 'sport', 'category', 'institution_id', 'tutor_id'
    ];

    /**
     * Get the institution associated with this evaluation
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the anthropometric data associated with this evaluation
     */
    public function anthropometricData() {
        return $this->hasOne(AnthropometricData::class, 'athlete_id');
    }

    /**
     * Get the jumpability data associated with this evaluation
     */
    public function jumpability() {
        return $this->hasOne(Jumpability::class);
    }

    /**
     * Get the reports associated with this evaluation
     */
    public function reports() {
        return $this->hasMany(Report::class, 'athlete_id');
    }
}