<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'citizen_id',
        'program_id',
        'rating',
        'comment',
    ];

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
