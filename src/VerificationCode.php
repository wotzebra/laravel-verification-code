<?php

namespace NextApps\VerificationCode;

use Illuminate\Support\Facades\Facade;

class VerificationCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'verification-code';
    }
}
