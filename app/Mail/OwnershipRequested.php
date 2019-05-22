<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OwnershipRequested extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $token;

    /**
     * Create a new message instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;

        $this->onConnection('sqs_mail');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $confirmLink = url('/some/path', ['token' => $this->token]);

        return $this->view('emails.ownership.requested', compact('confirmLink'));
    }
}
