<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class TrialEndingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $trialEndsAt;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->trialEndsAt = Carbon::parse($user->trial_ends_at)->format('F j, Y');
    }

    public function build()
    {
        return $this->subject('Your Vibe Lift Daily Trial Ends Today!')
                   ->markdown('emails.trial-ending-reminder');
    }
}