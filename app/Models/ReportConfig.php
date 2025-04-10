<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportConfig extends Model {
    protected $table = 'report_configs';
    protected $fillable = ['name', 'fields'];
    protected $casts = [
        'fields' => 'array' // Automatically cast JSON to array
    ];
}