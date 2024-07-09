<?php

namespace NextApps\VerificationCode;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \NextApps\VerificationCode\VerificationCodeManager send(string $verifiable, string $channel = 'mail')
 * @method static \NextApps\VerificationCode\VerificationCodeManager verify(string $code, string $verifiable, bool $deleteAfterVerification = true)
 *
 * @see \NextApps\VerificationCode\VerificationCodeManager
 */
class VerificationCode extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'verification-code';
    }
}
