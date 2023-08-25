<?php

namespace NextApps\VerificationCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationCodeCreated extends Notification implements ShouldQueue, VerificationCodeCreatedInterface
{
    use Queueable;

    public $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via() : array
    {
        return ['mail'];
    }

    public function toMail() : MailMessage
    {
        return (new MailMessage())
            ->subject(__('Your verification code'))
            ->greeting(__('Hello!'))
            ->line(__('Your verification code: :code', ['code' => $this->code]))
            ->line(__('Kind regards'));
    }
}
