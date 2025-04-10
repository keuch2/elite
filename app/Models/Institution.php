<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model {
    protected $table = 'institutions';
    protected $fillable = ['name'];

    public function athletes() {
        return $this->hasMany(Athlete::class, 'institution_id');
    }
}
