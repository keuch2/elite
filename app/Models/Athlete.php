<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Athlete extends Model {
    use HasUuids;
    
    protected $table = 'athletes'; // Explicitly set table name
    protected $fillable = [
        'athlete_profile_id', 'evaluation_id', 'evaluation_date',
        'age', 'grade', 'sport', 'category', 'institution_id'
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['evaluation_id'];
    }
    
    /**
     * Get the athlete profile this evaluation belongs to
     */
    public function profile() {
        return $this->belongsTo(AthleteProfile::class, 'athlete_profile_id');
    }

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
     * Get the reports associated with this evaluation
     */
    public function reports() {
        return $this->hasMany(Report::class, 'athlete_id');
    }
}