<?php

namespace NextApps\VerificationCode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WrongNotification extends Notification implements ShouldQueue
{
    use Queueable;
}
