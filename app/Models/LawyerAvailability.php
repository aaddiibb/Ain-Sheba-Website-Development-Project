<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerAvailability extends Model
{
    protected $table = 'lawyer_availability';

    protected $fillable = [
        'lawyer_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
