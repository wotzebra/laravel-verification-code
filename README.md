# Laravel verification code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps-be/laravel-verification-code.svg?style=flat-square)](https://packagist.org/packages/nextapps-be/laravel-verification-code)
[![Build Status](https://img.shields.io/travis/nextapps-be/laravel-verification-code/master.svg?style=flat-square)](https://travis-ci.org/nextapps-be/laravel-verification-code)
[![Quality Score](https://img.shields.io/scrutinizer/g/nextapps-be/laravel-verification-code.svg?style=flat-square)](https://scrutinizer-ci.com/g/nextapps-be/laravel-verification-code)
[![Total Downloads](https://img.shields.io/packagist/dt/nextapps-be/laravel-verification-code.svg?style=flat-square)](https://packagist.org/packages/nextapps-be/laravel-verification-code)

This package makes it possible to authenticate a user using a verification code.

## Installation

You can install this package using composer:

```bash
composer require nextapps/laravel-verification-code
```

The package will automatically register itself.

You can publish the migration with:
```bash
php artisan vendor:publish --provider="NextApps\VerificationCode\VerificationCodeServiceProvider" --tag="migrations"
```

After publishing the migration, run the migration with:
```bash
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="NextApps\VerificationCode\VerificationCodeServiceProvider" --tag="config"
```

## Usage

### Generate and send a verification code
```php
Verification::sendCode({user_email});
```
This will generate a verification code for the user. The code will be stored in the `verification_codes` table. An email with the generated code will then be sent to the user matching the given email address.

### Verify a verification code
```php
Verification::verify({code}, {user_email});
```
If the verification code is expired or does not match the user's email address, it will return `false`. If valid, it will return `true` and delete the code.

## Config settings

### Exclude characters
You sometimes may want to exclude one or more characters from the selected or default character set. This can be done simply by adding the character(s) to the `exclude_characters` array in the config (e.g. `excluded_characters => ['0', '9']`, verification codes will be issued between 1 and 8).

### Queue
In specific cases you may want to put the  verification code notifications on a queue. This can be done by defining the queue in the config (e.g. `queue => 'notifications'`).

### Test code and test verifiables
You sometimes may want to allow a user to log in immediately without letting them go through the verification code flow. To do this you can add their email to the `test_verifiables` array. You then need to define a `test_code`. The combination of the user's email and the test code will make it possible for the user to pass through.

### Config settings
```php
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
    | This option makes it possible to bypass verification using an email.
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
    | This option makes it possible to bypass verification using a custom code.
    |
    */
    'test_code' => null,
];
```

## Testing
You can run tests with:
``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Evert Arnould](https://github.com/earnould)
- [Günther Debrauwer](https://github.com/gdebrauwer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
