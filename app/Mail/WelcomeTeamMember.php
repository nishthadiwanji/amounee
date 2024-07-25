<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TeamMember\TeamMember;
use App\Models\Auth\SentinelUser;

class WelcomeTeamMember extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $team_member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SentinelUser $user,TeamMember $team_member)
    {
        $this->user = $user;
        $this->team_member = $team_member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.team-member.welcome')
            ->subject('Welcome!')
            ->with(['user'=>$this->user,'team_member'=>$this->team_member]);
    }
}
