# Laravel verification code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps/laravel-verification-code.svg?style=flat-square)](https://packagist.org/packages/nextapps/laravel-verification-code)
[![GitHub 'Run Tests' Workflow Status](https://img.shields.io/github/workflow/status/nextapps-be/laravel-verification-code/run-tests?label=tests&style=flat-square&logo=github)](https://github.com/nextapps-be/laravel-verification-code/actions?query=workflow%3Arun-tests)
[![Total Downloads](https://img.shields.io/packagist/dt/nextapps/laravel-verification-code.svg?style=flat-square)](https://packagist.org/packages/nextapps/laravel-verification-code)

This package makes it possible to authenticate a user via a verification code.

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
VerificationCode::send($email);
```
This will generate a verification code for the user. The code will be stored in the `verification_codes` table. An email with the generated code will then be sent to the user matching the given email address.

### Verify a verification code
```php
VerificationCode::verify($code, $email);
```
If the verification code is expired or does not match the user's email address, it will return `false`. If valid, it will return `true` and delete the code.

## Config settings

### Length
This value defines the length of every generated verification code.

### Characters
You can define which characters are used to generate a verification code. By default, certain characters are excluded (0, O, I, L) because they look too similar.

### Expire hours
A verification code is only valid for a certain amount of time. You can define after how many hours a verification code will expire.

### Max codes per verifiable

By default, only one verification code can be active per verifiable. If you want to allow multiple active codes per verifiable, then you can
change this setting to a different number (or to `null` if you want unlimited codes per verifiable).

### Custom Notification
If you want to use a custom notification to send the verification code, you can create your own notification class which should extend the `VerificationCodeCreatedInterface`. Make sure you don't forget to pass the verification code to the mail.

### Queue
If your notification is queueable, you can  define the queue that will be used for the notification.

### Test verifiables and test code
You sometimes may want to allow a user to log in immediately without letting them go through the verification code flow. To do this you can add the verifiable (e.g. email address) to the `test_verifiables` array. You then need to define a `test_code`. The combination of the verifiable and the test code will make it possible for the user to pass through.

## Testing
You can run tests with:
``` bash
composer test
```
## Linting

```bash
composer lint
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Evert Arnould](https://github.com/earnould)
- [Günther Debrauwer](https://github.com/gdebrauwer)
- [Philippe Damen](https://github.com/yinx)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
