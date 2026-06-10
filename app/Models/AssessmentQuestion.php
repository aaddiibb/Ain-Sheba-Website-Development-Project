<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'assessment_id',
        'question',
        'type',
        'points',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function options()
    {
        return $this->hasMany(AssessmentOption::class, 'question_id');
    }
}
