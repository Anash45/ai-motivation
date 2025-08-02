<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'trial_ends_at',
        'age_range',
        'profession',
        'interests',
        'plan_type',
        'is_subscribed',
        'subscription_ends_at',
        'stripe_subscription_id'
    ];


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin'; // Or however you identify admin users
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
    public function isOnTrial(): bool
    {
        return $this->plan_type === 'trial' && $this->trial_ends_at && now()->lessThan($this->trial_ends_at);
    }

    /**
     * Determine if the user is currently subscribed.
     */
    public function isSubscribed(): bool
    {
        return $this->is_subscribed && $this->subscription_ends_at && now()->lessThan($this->subscription_ends_at);
    }

    /**
     * Determine if the subscription has expired.
     */
    public function hasExpiredSubscription(): bool
    {
        return $this->subscription_ends_at && now()->greaterThanOrEqualTo($this->subscription_ends_at);
    }

}
