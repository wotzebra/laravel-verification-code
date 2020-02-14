# Laravel verification code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps/laravel-verification-code.svg?style=flat-square)](https://packagist.org/packages/nextapps/laravel-verification-code)
[![Build Status](https://img.shields.io/travis/nextapps-be/laravel-verification-code/master.svg?style=flat-square)](https://travis-ci.org/nextapps-be/laravel-verification-code)
[![Quality Score](https://img.shields.io/scrutinizer/g/nextapps-be/laravel-verification-code.svg?style=flat-square)](https://scrutinizer-ci.com/g/nextapps-be/laravel-verification-code)
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
The length of the verification codes. The standard length for a verification code is `6`.

### Type
The character set the package will use to generate verification codes. The supported character sets are `numeric`, `alphabetical` and `alphanumeric`. The `numeric` character set is used by default.

### Exclude characters
You sometimes may want to exclude one or more characters from the selected or default character set. This can be done simply by adding the character(s) to the `exclude_characters` array in the config (e.g. `excluded_characters => ['0', '9']`, verification codes will be issued between 1 and 8).

### Expire hours
The amount of hours it takes for a verification code to expire. You're free to increase this in the config.

### Queue
In specific cases you may want to put the  verification code notifications on a queue. This can be done by defining the queue in the config (e.g. `queue => 'notifications'`).

### Test verifiables and test codes
You sometimes may want to allow a user to log in immediately without letting them go through the verification code flow. To do this you can add their email to the `test_verifiables` array. You then need to define a `test_code`. The combination of the user's email and the test code will make it possible for the user to pass through.

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
