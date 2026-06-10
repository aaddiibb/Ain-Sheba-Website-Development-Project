<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'content',
        'resource_url',
        'order_index',
        'duration_minutes',
        'is_free',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }

    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class);
    }
}
