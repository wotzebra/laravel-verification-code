<?php

namespace NextApps\VerificationCode;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;
use NextApps\VerificationCode\Notifications\VerificationCodeCreatedInterface;
use RuntimeException;

class VerificationCodeManager
{
    /**
     * Create and send a verification code.
     */
    public function send(string $verifiable, string $channel = 'mail') : void
    {
        if ($this->isTestVerifiable($verifiable)) {
            return;
        }

        $code = VerificationCode::createFor($verifiable);

        $notificationClass = $this->getNotificationClass();
        $notification = new $notificationClass($code);

        if ($notification instanceof ShouldQueue) {
            $notification->onQueue(config('verification-code.queue', null));
        }

        Notification::route($channel, $verifiable)->notify($notification);
    }

    public function verify(string $code, string $verifiable, bool $deleteAfterVerification = true) : bool
    {
        if ($this->isTestVerifiable($verifiable)) {
            return $this->isTestCode($code);
        }

        $codeIsValid = VerificationCode::query()
            ->for($verifiable)
            ->notExpired()
            ->cursor()
            ->contains(function ($verificationCode) use ($code) {
                return Hash::check($code, $verificationCode->code);
            });

        if (! $codeIsValid) {
            return false;
        }

        if ($deleteAfterVerification) {
            VerificationCode::for($verifiable)->delete();
        }

        return true;
    }

    protected function isTestVerifiable(string $verifiable) : bool
    {
        $testVerifiables = config('verification-code.test_verifiables', []);

        $testVerifiables = array_map(function ($email) {
            return strtolower($email);
        }, $testVerifiables);

        return in_array(strtolower($verifiable), $testVerifiables);
    }

    protected function isTestCode(string $code) : bool
    {
        if (empty(config('verification-code.test_code'))) {
            return false;
        }

        return $code === config('verification-code.test_code');
    }

    protected function getNotificationClass() : string
    {
        $notificationClass = config('verification-code.notification', VerificationCodeCreated::class);

        if (! is_subclass_of($notificationClass, VerificationCodeCreatedInterface::class)) {
            $interface = VerificationCodeCreatedInterface::class;

            throw new RuntimeException("The notification class must implement the `{$interface}` interface");
        }

        return $notificationClass;
    }
}
