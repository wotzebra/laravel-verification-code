<?php

namespace Wotz\VerificationCode;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Wotz\VerificationCode\VerificationCodeManager send(string $verifiable, string $channel = 'mail')
 * @method static \Wotz\VerificationCode\VerificationCodeManager verify(string $code, string $verifiable)
 *
 * @see \Wotz\VerificationCode\VerificationCodeManager
 */
class VerificationCode extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'verification-code';
    }
}
