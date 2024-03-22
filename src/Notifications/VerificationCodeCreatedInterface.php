<?php

namespace NextApps\VerificationCode\Notifications;

interface VerificationCodeCreatedInterface
{
    public function __construct(string $code, ...$args);
}
