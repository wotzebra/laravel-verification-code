# Changelog

All notable changes to `laravel-verification-code` will be documented in this file

## 2.1.0 - 2023-12-15

- Added: PHP 8.3 support ([#34](https://github.com/nextapps-be/laravel-verification-code/pull/34))

## 2.0.0 - 2023-08-25
- Verification code Model can be customized ([#33](https://github.com/nextapps-be/laravel-verification-code/pull/33))
- Expire hours changed to Expire seconds ([#32](https://github.com/nextapps-be/laravel-verification-code/pull/32))
- Add unicode support ([#30](https://github.com/nextapps-be/laravel-verification-code/pull/30))
- Drop support for PHP 7.x and Drop support for Laravel 7, Laravel 8 and Laravel 9 ([#31](https://github.com/nextapps-be/laravel-verification-code/pull/31))

## 1.3.0 - 2023-02-19

- Add PHP 8.2 support ([#27](https://github.com/nextapps-be/laravel-verification-code/pull/27))
- Add Laravel 10 support ([#26](https://github.com/nextapps-be/laravel-verification-code/pull/26))

## 1.2.3 - 2022-05-18

- Changed: Do not purge codes that have not expired yet ([#24](https://github.com/nextapps-be/laravel-verification-code/pull/24))

## 1.2.2 - 2022-05-06

- Added: parameter to verify method to prevent deleting of the code after verification ([#23](https://github.com/nextapps-be/laravel-verification-code/pull/23))
- Added: command to prune old codes from the database ([#23](https://github.com/nextapps-be/laravel-verification-code/pull/23))

## 1.2.1 - 2022-03-01

 - Fix composer.json file to ensure package can still be used in Laravel 7/8

## 1.2.0 - 2022-03-01

 - Add PHP 8.1 and Laravel 9 support ([#20](https://github.com/nextapps-be/laravel-verification-code/pull/20))

## 1.1.0 - 2021-02-15

 - Add PHP 8.0 support ([#17](https://github.com/nextapps-be/laravel-verification-code/pull/17))

## 1.0.0 - 2021-01-15

 - No changes

## 0.3.4 - 2020-12-16

- Verifying test verifiables is now case insensitive ([#13](https://github.com/nextapps-be/laravel-verification-code/pull/13))

## 0.3.3 - 2020-10-23

- Verify against all codes of verifiable (instead of only first code) ([#12](https://github.com/nextapps-be/laravel-verification-code/pull/12))

## 0.3.2 - 2020-10-22

- Fix deletion of old verification codes (again) ([#11](https://github.com/nextapps-be/laravel-verification-code/pull/11))

## 0.3.1 - 2020-10-20

- Fix deletion of old verification codes ([#10](https://github.com/nextapps-be/laravel-verification-code/pull/10))

## 0.3.0 - 2020-10-20

- Add config option to define max amount of active verification codes per verifiable ([#9](https://github.com/nextapps-be/laravel-verification-code/pull/9))
- Big code refactor with improvements to config-file setup ([#7](https://github.com/nextapps-be/laravel-verification-code/pull/7))

## 0.2.0 - 2020-09-01

- Add config option to allow customization of the notification class. ([#4](https://github.com/nextapps-be/laravel-verification-code/pull/4))

## 0.1.0 - 2020-02-20

- Initial release
