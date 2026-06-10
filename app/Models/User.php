<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_picture',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    // As lawyer: programs they created
    public function programs()
    {
        return $this->hasMany(Program::class, 'lawyer_id');
    }

    // As citizen: programs they enrolled in
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'citizen_id');
    }

    // As citizen: assessment attempts
    public function assessmentAttempts()
    {
        return $this->hasMany(AssessmentAttempt::class, 'citizen_id');
    }

    // As citizen: certificates earned
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'citizen_id');
    }

    // As citizen: feedback given
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'citizen_id');
    }
}
