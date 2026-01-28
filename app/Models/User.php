<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //  AJOUT role + TOUS tes champs
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'job_title',
        'department', 
        'bio',
        'location',
        'role'  //  NOUVEAU pour admin
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
        ];
    }

    //  FONCTION ADMIN - CRUCIALE
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relation avec les avis
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
