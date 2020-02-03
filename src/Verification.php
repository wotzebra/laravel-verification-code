<?php

namespace NextApps\VerificationCode;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;

class Verification
{
    /**
     * Create and send a verification code via mail.
     *
     * @param string $verifiable
     *
     * @return void
     */
    public function sendCode($verifiable)
    {
        $testVerifiables = config('verification-code.test_verifiables');

        if (! is_array($testVerifiables)) {
            $testVerifiables = [];
        }

        if (in_array($verifiable, $testVerifiables)) {
            return;
        }

        $code = VerificationCode::createFor($verifiable);

        Notification::route('mail', $verifiable)
            ->notify((new VerificationCodeCreated($code))
            ->onQueue(config('verification-code.queue')));
    }

    /**
     * Verify the code.
     *
     * @param string $code
     * @param string $verifiable
     *
     * @return bool
     */
    public function verify(string $code, string $verifiable)
    {
        $testVerifiables = config('verification-code.test_verifiables');

        if (! is_array($testVerifiables)) {
            $testVerifiables = [];
        }

        if (in_array($verifiable, $testVerifiables) && config('verification-code.test_code')) {
            return $code === config('verification-code.test_code');
        }

        $verificationCode = VerificationCode::from($verifiable);

        if ($verificationCode === null || $verificationCode->expired || ! Hash::check($code, $verificationCode->code)) {
            return false;
        }

        $verificationCode->delete();

        return true;
    }
}
