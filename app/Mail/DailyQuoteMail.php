<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyQuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $quote;
    public $quoteLink;
    public $audioPath;

    public function __construct($user, $quote, $quoteLink, $audioPath)
    {
        $this->user = $user;
        $this->quote = $quote;
        $this->quoteLink = $quoteLink;
        $this->audioPath = $audioPath;
    }

    public function build()
    {
        return $this->subject('Your Daily Motivation is Here')
            ->markdown('emails.daily_quote')
            ->attach($this->audioPath, [
                'as' => 'motivation.mp3',
                'mime' => 'audio/mpeg',
            ]);
    }
}