<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

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

    protected $appends = ['created_at_relable'];
    // Its Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function food()
    {
        return $this->hasMany(Food::class);
    }
    public function quantity()
    {
        return $this->hasMany(Quantity::class);
    }
    public function table()
    {
        return $this->hasMany(Table::class);
    }
    public function reservation()
    {
        return $this->hasMany(Reservations::class);
    }

    // Appends relationship
    public function getCreatedAtRelableAttribute()
    {
        return $this->created_at?->diffForHumans();
    }
}
