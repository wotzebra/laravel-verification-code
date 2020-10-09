<?php

namespace NextApps\VerificationCode;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \NextApps\VerificationCode\VerificationCodeManager send(string $verifiable, string $channel = 'mail')
 * @method static \NextApps\VerificationCode\VerificationCodeManager verify(string $code, string $verifiable)
 *
 * @see \NextApps\VerificationCode\VerificationCodeManager
 */
class VerificationCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'verification-code';
    }
}
