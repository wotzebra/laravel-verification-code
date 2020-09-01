<?php

namespace NextApps\VerificationCode\Notifications;

interface VerificationCodeCreatedInterface
{
    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct(string $code);
}
