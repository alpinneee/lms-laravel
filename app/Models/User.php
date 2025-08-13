<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'instructure_id',
        'user_type_id',
        'last_login',
        'token',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function instructure()
    {
        return $this->belongsTo(Instructure::class);
    }

    public function participant()
    {
        return $this->hasOne(Participant::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->userType?->usertype === 'admin';
    }

    public function isInstructor()
    {
        return $this->userType?->usertype === 'instructor';
    }

    public function isParticipant()
    {
        return $this->userType?->usertype === 'participant';
    }

    public function getUserRole()
    {
        return $this->userType?->usertype;
    }

    public function isUnassigned()
    {
        return $this->userType?->usertype === 'unassigned';
    }
    
    public function isActive()
    {
        return $this->status === 'active';
    }
    
    public function isInactive()
    {
        return $this->status === 'inactive';
    }
}
