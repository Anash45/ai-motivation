<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\User;

class TrialWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $trialEndsAt;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->trialEndsAt = Carbon::parse($user->trial_ends_at)->format('M d, Y');
    }

    public function build()
    {
        return $this->subject('Welcome to VibeLift Daily')
            ->markdown('emails.trial-welcome');
    }
}
