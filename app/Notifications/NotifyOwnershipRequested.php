<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class NotifyOwnershipRequested extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    private $code;

    /**
     * Create a new notification instance.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;

        $this->onConnection('sqs_sms');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return [TwilioChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return TwilioSmsMessage
     */
    public function toTwilio()
    {
        return (new TwilioSmsMessage())
            ->content('Ownership Confirmation Code: ' . $this->code);
    }
}
