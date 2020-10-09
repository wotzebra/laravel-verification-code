<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Code Length
    |--------------------------------------------------------------------------
    |
    | This value defines the length of every generated verification code.
    |
    */
    'length' => 6,

    /*
    |--------------------------------------------------------------------------
    | Code Type
    |--------------------------------------------------------------------------
    |
    | This class defines the characterset that will be used to generate the
    | verification codes.
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
    | the characterset that is determined by the selected code type.
    |
    | Example: '0AZ'
    |
    */
    'excluded_characters' => '',

    /*
    |--------------------------------------------------------------------------
    | Expiry time
    |--------------------------------------------------------------------------
    |
    | A verification code is only valid for a certain amount of time. Here
    | you define after how many hours a verification code will expire.
    |
    */
    'expiry_hours' => 1,

    /*
    |--------------------------------------------------------------------------
    | Custom Notification
    |--------------------------------------------------------------------------
    |
    | Here you can customize the notification class that will be used to sent
    | a verification code.
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
    | If your notification is queueable, you can here define the queue that
    | will be used for the notification.
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
