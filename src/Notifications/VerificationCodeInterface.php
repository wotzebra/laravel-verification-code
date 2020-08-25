<?php

namespace NextApps\VerificationCode\Notifications;

interface VerificationCodeInterface
{
    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct(string $code);
}
