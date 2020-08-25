<?php

namespace NextApps\VerificationCode;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use NextApps\VerificationCode\Exceptions\InvalidClassException;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;
use NextApps\VerificationCode\Notifications\VerificationCodeInterface;

class VerificationCodeManager
{
    /**
     * Create and send a verification code via mail.
     *
     * @param string $verifiable
     * @param string $channel
     *
     * @return void
     */
    public function send($verifiable, $channel = 'mail')
    {
        $testVerifiables = config('verification-code.test_verifiables', []);
        $notificationClass = config('verification-code.notification', VerificationCodeCreated::class);
        $queue = config('verification-code.queue', null);

        if (! is_subclass_of($notificationClass, VerificationCodeInterface::class)) {
            throw InvalidClassException::handle();
        }

        if (in_array($verifiable, $testVerifiables)) {
            return;
        }

        $code = VerificationCode::createFor($verifiable);

        if ($queue !== null) {
            Notification::route($channel, $verifiable)
                ->notify((new $notificationClass($code))->onQueue($queue));
        } else {
            Notification::route($channel, $verifiable)
                ->notifyNow(new $notificationClass($code));
        }
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
        $testVerifiables = config('verification-code.test_verifiables', []);

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
