<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'citizen_id',
        'assessment_id',
        'score',
        'passed',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'attempted_at' => 'datetime',
        ];
    }

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
