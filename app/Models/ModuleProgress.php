<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleProgress extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'registration_id',
        'module_id',
        'completed_at',
        'time_spent_seconds',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
