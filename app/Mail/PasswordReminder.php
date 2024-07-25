<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Auth\SentinelUser;
use Cartalyst\Sentinel\Reminders\EloquentReminder;

class PasswordReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $reminder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SentinelUser $user,EloquentReminder $reminder)
    {
        $this->user = $user;
        $this->reminder = $reminder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reminder')
            ->subject('Request for forgotten password | '.config('app.name'))
            ->with(['user'=>$this->user,'reminder'=>$this->reminder]);
    }
}
