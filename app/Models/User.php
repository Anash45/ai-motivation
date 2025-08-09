<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;


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
        'voice_id',
        'plan_type',
        'is_subscribed',
        'subscription_ends_at',
        'stripe_subscription_id',
        'paypal_subscription_id',
        'paypal_plan_id',
        'paypal_status',
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

    public function isOnTrial()
    {
        return $this->plan_type === 'trial';
    }

    public function isSubscribed()
    {
        return $this->plan_type === 'subscribe'
            && $this->is_subscribed == 1
            && Carbon::parse($this->subscription_ends_at)->isFuture();
    }

    public function isCancelled()
    {
        return $this->plan_type !== 'subscribe'
            && $this->subscription_ends_at !== null 
            && now()->lessThanOrEqualTo($this->subscription_ends_at)
            && $this->is_subscribed == 1;
    }

    /**
     * Determine if the subscription has expired.
     */
    public function hasExpiredSubscription(): bool
    {
        return $this->plan_type === 'subscribe'
            && $this->is_subscribed == 1
            && $this->subscription_ends_at !== null 
            && now()->greaterThan($this->subscription_ends_at);
    }

    public function voice()
    {
        return $this->belongsTo(Voice::class);
    }

}
