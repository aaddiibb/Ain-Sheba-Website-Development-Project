<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'citizen_id',
        'lawyer_id',
        'booked_date',
        'time_slot',
        'status',
        'fee',
        'citizen_notes',
        'lawyer_response',
    ];

    protected function casts(): array
    {
        return [
            'booked_date' => 'date',
            'fee'         => 'decimal:2',
        ];
    }

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
