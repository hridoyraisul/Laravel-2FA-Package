# Laravel 2FA

This package provides a simple and intuitive way of adding two-factor authentication to your Laravel application. It's perfect for developers who want a lightweight package that gets the job done without any unnecessary complexity.

## Installation

Use the package manager [composer](https://getcomposer.org/installer) to install this package.
```bash
composer require raisulhridoy/laravel-2fa
```

Add the service provider in config/app.php file in the providers array as below:
```bash
RaisulHridoy\Laravel2FA\TwoFactorAuthServiceProvider::class,
```

Publish the package configuration
```bash
php artisan vendor:publish --provider="RaisulHridoy\Laravel2FA\TwoFactorAuthServiceProvider"
```

Specify table name corresponding to the 2FA functionality in ".env" file. By default, it will be 'users' respectively and "google2fa_secret", "google2fa_enabled" & "google2fa_verify_status" column will be added in this table.
```bash
TFA_WITH_TABLE=
```
For example, if you want to use "users" table for the 2FA functionality, then you have to specify like this in ".env" file.
```bash
TFA_WITH_TABLE=users
```

Run these commands to clear the cache and migrate the database.
```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate
```

# Basic Usage
```php
# Initialize the namespace
use RaisulHridoy\Laravel2FA\Http\App\TFA;
```
Full instruction coming soon ...............


