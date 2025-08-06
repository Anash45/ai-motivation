<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Confirm your email for Vibe Lift Daily')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Thanks for joining Vibe Lift Daily! To get started, please verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not create an account, you can ignore this message.')
            ->salutation('— Vibe Lift Daily Team');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
}
