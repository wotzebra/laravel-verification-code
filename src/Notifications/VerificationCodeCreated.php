<?php

namespace NextApps\VerificationCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

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
     *
     * @return void
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Your login code'))
            ->greeting(Lang::get('Hello!'))
            ->line(Lang::get('Your login code:'.' '.$this->code))
            ->line(Lang::get('Kind regards'));
    }
}
