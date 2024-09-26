# Upgrading

## From v2 to v3

- Install `wotz/laravel-verification-code` instead of `nextapps/laravel-verification-code`
- Replace all occurrences of `NextApps\VerificationCode` namespace with new `Wotz\VerificationCode` namespace

## From v1 to v2

- Config variable `expire_hours` has been renamed to  `expire_seconds`, rename your config variable and multiply your value by 3600 to have the same behaviour.
- The minimum supported version of PHP has changed to PHP 8.0
- The minimum supported version of Laravel has changed to Laravel 10
- Config variable `model` has been added
