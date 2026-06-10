<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'passing_score',
        'time_limit_minutes',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
}
