<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',

        'first_name',
        'last_name',
        'phone_number',

        'address',
        'city',
        'zip_code',
        'country',

        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     * 
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    /**
     * Uppercase the last name when setting it.
     * 
     */
    public function setLastNameAttribute(string $value): void
    {
        $this->attributes['last_name'] = strtoupper($value);
    }
    public function markEmailAsVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
    public function markEmailAsUnverified(): void
    {
        $this->forceFill([
            'email_verified_at' => null,
        ])->save();
    }
    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        event(new Registered($this)); // send email verification to user

        // $this->notify(new \App\Notifications\VerifyEmail);
        // send verify email 
        //$this->notify(new \App\Notifications\VerifyEmail);
    }
    /**
     * Send the welcome email notification.
     */
    public function sendWelcomeEmail(): void
    {
        // try sending welcome email for 10 times


        // send welcome email
        //$this->notify(new \App\Notifications\WelcomeEmail);
    }


    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }


    public function shop()
    {
        return $this->hasOne(Shop::class, 'owner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // Favorites
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
}
