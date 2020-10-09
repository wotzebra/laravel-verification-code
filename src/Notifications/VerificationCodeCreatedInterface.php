<?php

namespace NextApps\VerificationCode\Notifications;

interface VerificationCodeCreatedInterface
{
    /**
     * Create a new notification instance.
     *
     * @param string $code
     */
    public function __construct(string $code);
}
