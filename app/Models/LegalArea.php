<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalArea extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
