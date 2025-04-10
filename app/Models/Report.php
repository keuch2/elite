<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    protected $table = 'reports';
    protected $fillable = ['athlete_id', 'file_path', 'sent_to_tutor', 'sent_to_institution'];

    public function athlete() {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }
}
