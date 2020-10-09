<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Code Length
    |--------------------------------------------------------------------------
    |
    | This option defines the length of the generated verification codes.
    |
    */
    'length' => 6,

    /*
    |--------------------------------------------------------------------------
    | Code Type
    |--------------------------------------------------------------------------
    |
    | This option defines the character set for the verification codes.
    |
    | The supported code types are:
    |   - \NextApps\VerificationCode\CodeTypes\Numeric
    |   - \NextApps\VerificationCode\CodeTypes\Alphabetical
    |   - \NextApps\VerificationCode\CodeTypes\Alphanumeric
    |
    */
    'type' => \NextApps\VerificationCode\CodeTypes\Numeric::class,

    /*
    |--------------------------------------------------------------------------
    | Excluded Characters
    |--------------------------------------------------------------------------
    |
    | This option makes it possible to exclude specific characters from
    | the selected or default character set.
    |
    | Example: ['0','A','Z']
    |
    */
    'excluded_characters' => [],

    /*
    |--------------------------------------------------------------------------
    | Expiry time
    |--------------------------------------------------------------------------
    |
    | The amount of hours it takes for a verification code to expire.
    |
    */
    'expiry_hours' => 1,

    /*
    |--------------------------------------------------------------------------
    | Custom Notification
    |--------------------------------------------------------------------------
    |
    | This class contains the notification sent to users upon creation
    | of the verification code.
    |
    | It should implement the interface:
    |   - \NextApps\VerificationCode\Notifications\VerificationCodeCreatedInterface
    |
    */
    'notification' => \NextApps\VerificationCode\Notifications\VerificationCodeCreated::class,

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | The queue on which the verification code notification is pushed on.
    |
    */
    'queue' => null,

    /*
    |--------------------------------------------------------------------------
    | Test Verifiables
    |--------------------------------------------------------------------------
    |
    | This option enables you to skip verification for certain verifiables.
    |
    | Example: ['person@example.com','otherperson@example.com']
    |
    */
    'test_verifiables' => [],

    /*
    |--------------------------------------------------------------------------
    | Test Verification Code
    |--------------------------------------------------------------------------
    |
    | This option enables the test verifiables to bypass verification using
    | this test code.
    |
    */
    'test_code' => null,
];
