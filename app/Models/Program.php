<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'lawyer_id',
        'legal_area_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'level',
        'language',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function legalArea()
    {
        return $this->belongsTo(LegalArea::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function averageRating()
    {
        return $this->feedback()->avg('rating');
    }
}
