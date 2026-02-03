<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $activationUrl)
    {}

    public function build()
    {
        return $this->subject('Driver Invitation')
            ->markdown('emails.driver_invite')
            ->with(['activationUrl' => $this->activationUrl]);
    }
}
