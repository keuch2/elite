<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model {
    protected $table = 'athletes'; // Explicitly set table name
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'identity_document', 'birth_date', 'evaluation_date',
        'age', 'grade', 'sport', 'category', 'institution_id', 'father_name', 'mother_name', 'tutor_id'
    ];

    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }

    public function anthropometricData() {
        return $this->hasOne(AnthropometricData::class, 'athlete_id');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'athlete_id');
    }
}