<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default length
    |--------------------------------------------------------------------------
    |
    | This option defines the length of the generated verification codes.
    | The default is set to 6.
    |
    */
    'length' => null,

    /*
    |--------------------------------------------------------------------------
    | Code type
    |--------------------------------------------------------------------------
    |
    | This option defines the character set for the verification codes.
    | The default character set is set 'numeric'.
    |
    | Supported: 'numeric', 'alphabetical', 'alphanumeric'
    |
    */
    'type' => null,

    /*
    |--------------------------------------------------------------------------
    | Exclude characters from character set
    |--------------------------------------------------------------------------
    |
    | This option makes it possible to exclude specific characters from
    | the selected or default character set.
    |
    | Example: ['0','A','Z']
    |
    */
    'exclude_characters' => [],

    /*
    |--------------------------------------------------------------------------
    | Expiry time
    |--------------------------------------------------------------------------
    |
    | The amount of hours it takes for a verification code to expire.
    |
    */
    'expire_hours' => 1,

    /*
    |--------------------------------------------------------------------------
    | Custom notification
    |--------------------------------------------------------------------------
    |
    | This class contains the notification sent to users upon creation
    | of the verification code.
    |
    | It should implement Illuminate\Contracts\Queue\ShouldQueue and NextApps\VerificationCode\Notifications\VerificationCodeInterface.
    |
    */
    'notification' => \NextApps\VerificationCode\Notifications\VerificationCodeCreated::class,

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | The queue on which the verification code notification is pushed on.
    | If this option is set to 'null' the notification will not be
    | pushed on a queue and sent directly to the user.
    |
    */
    'queue' => null,

    /*
    |--------------------------------------------------------------------------
    | Test verification email
    |--------------------------------------------------------------------------
    |
    | This option enables it to bypass verification using an email.
    |
    | Example: ['person@example.com','otherperson@example.com']
    |
    */
    'test_verifiables' => [],

    /*
    |--------------------------------------------------------------------------
    | Test verification code
    |--------------------------------------------------------------------------
    |
    | This option enables it to bypass verification using a custom code.
    |
    */
    'test_code' => null,
];
