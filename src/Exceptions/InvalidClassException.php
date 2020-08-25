<?php

namespace NextApps\VerificationCode\Exceptions;

use Exception;

class InvalidClassException extends Exception
{
    public static function handle(): self
    {
        return new static("The notification should extend the VerificationCodeInterface.");
    }
}
