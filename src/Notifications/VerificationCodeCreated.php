<?php

namespace NextApps\VerificationCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerificationCodeCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public $code;

    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
            ->subject(__('Your login code'))
            ->greeting(__('Hello!'))
            ->line(__('Your login code: :code', ['code' => $this->code]))
            ->line(__('Kind regards'));
    }
}
