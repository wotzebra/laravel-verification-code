<?php

namespace Wotz\VerificationCode\Notifications;

interface VerificationCodeCreatedInterface
{
    public function __construct(string $code);
}
