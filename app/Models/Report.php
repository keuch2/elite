<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    protected $table = 'reports';
    protected $fillable = [
        'athlete_id', 
        'template_id', 
        'report_data', 
        'file_path', 
        'sent_to_tutor', 
        'sent_to_institution',
        'created_by'
    ];
    
    protected $casts = [
        'report_data' => 'array',
        'sent_to_tutor' => 'boolean',
        'sent_to_institution' => 'boolean',
    ];

    public function athlete() {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }
    
    public function template() {
        return $this->belongsTo(ReportConfig::class, 'template_id');
    }
    
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
