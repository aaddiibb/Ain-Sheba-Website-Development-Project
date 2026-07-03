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
        'consultation_fee',
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
            'is_active'        => 'boolean',
            'consultation_fee' => 'decimal:2',
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

    // As lawyer: availability slots
    public function availability()
    {
        return $this->hasMany(LawyerAvailability::class, 'lawyer_id');
    }

    // As lawyer: consultation bookings
    public function consultationsAsLawyer()
    {
        return $this->hasMany(Consultation::class, 'lawyer_id');
    }

    // As citizen: consultation bookings
    public function consultationsAsCitizen()
    {
        return $this->hasMany(Consultation::class, 'citizen_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }
}
