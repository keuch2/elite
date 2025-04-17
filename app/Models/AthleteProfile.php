<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AthleteProfile extends Model
{
    protected $table = 'athlete_profiles';
    
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'identity_document', 'birth_date',
        'institution_id', 'tutor_id', 'father_name', 'mother_name'
    ];

    /**
     * Get all evaluations for this athlete profile
     */
    public function evaluations()
    {
        return $this->hasMany(Athlete::class, 'athlete_profile_id');
    }

    /**
     * Get the institution associated with the athlete
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the tutor associated with the athlete
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    
    /**
     * Get the full name of the athlete
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
