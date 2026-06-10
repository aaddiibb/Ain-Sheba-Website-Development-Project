<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'citizen_id',
        'program_id',
        'registered_at',
        'completed_at',
        'progress_percent',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class);
    }

    // Returns the first module without a completed module_progress entry for this registration
    public function nextModule(): ?Module
    {
        $completedModuleIds = $this->moduleProgress()
            ->whereNotNull('completed_at')
            ->pluck('module_id');

        return $this->program
            ->modules()
            ->whereNotIn('id', $completedModuleIds)
            ->orderBy('order_index')
            ->first();
    }
}
