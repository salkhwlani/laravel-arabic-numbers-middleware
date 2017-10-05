# Transform between (Arabic-english)/(eastern-Indian) numbers middleware for laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleCi]][link-styleCi]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

>  A tiny package to transforms [arabic](https://en.wikipedia.org/wiki/Arabic_numerals)/[eastern*](https://en.wikipedia.org/wiki/Eastern_Arabic_numerals) to [eastern*](https://en.wikipedia.org/wiki/Eastern_Arabic_numerals)/[arabic](https://en.wikipedia.org/wiki/Arabic_numerals) numbers for i.e `١٢٣٤٥٦٧٨` to `12345678` before validations to avoid invalid integers error when pass `eastern` numbers by (ios/mac) users

* These numbers are known as أرقام هندية ("Indian numbers") in Arabic. They are sometimes also called "Indic numerals" in English. However, that is sometimes discouraged as it can lead to confusion with Indian numerals


## Install

Via Composer

``` bash
$ composer require yemenifree/laravel-arabic-numbers-middleware
```

## Usage

If you do not run Laravel 5.5 (or higher), then add the service provider in `config/app.php`:

```php
Yemenifree\LaravelArabicNumbersMiddleware\ServiceProvider::class,
```

If you do run the package on Laravel 5.5+, [package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) takes care of the magic of adding the service provider.

That's it! by default package automatic transform all `eastern` numbers to `arabic` for all requests

Package includes two middleware
 
- "eastern-to-arabic" to transform numbers from eastern to arabic (i.e `١٢٣` to `123`)
- "arabic-to-eastern" to transform numbers from arabic to eastern (i.e `123` to `١٢٣`)

You can use middleware for custom router for example 

```php
Route::any('/login', ['middleware' => 'eastern-to-arabic', function () {
    // your code
}]);
```

you can ignore fields from transform by pass fields name as parameters to middleware for example

```php
// ignore transform pass field 
Route::any('/login', ['middleware' => 'arabic-to-eastern:pass', function () {
    // your code
}]);
        
// you can also ignore multi fields
Route::any('/login', ['middleware' => 'arabic-to-eastern:pass,test', function () {
    // your code
}]);
```

> inline ignore fields not work if auto_register_middleware enable and middleware you want to use inside auto_middleware option, instead you can set ignore fields in config file arabic-numbers-middleware.php

## Optional

if you want customizes configuration you can publish the configuration

```bash
$ php artisan vendor:publish --provider="Yemenifree\LaravelArabicNumbersMiddleware\ServiceProvider"
```

This is the content of the published config file `arabic-numbers-middleware.php`:

```php
return [
    /*
     |--------------------------------------------------------------------------
     | enable auto register middleware for all requests
     |--------------------------------------------------------------------------
     |
     | if you want auto register for custom middleware group
     |      'auto_register_middleware' => ['web'], //for web group only
     |      'auto_register_middleware' => true, //  all groups
     |      'auto_register_middleware' => false, // none
     */
    'auto_register_middleware' => true,

    /*
     |--------------------------------------------------------------------------
     | list of middleware they will register for all requests automatic by package
     |--------------------------------------------------------------------------
     |
     |  Supported Middleware: "arabic-to-eastern", "eastern-to-arabic"
     */
    'auto_middleware' => Yemenifree\LaravelArabicNumbersMiddleware\Middleware\TransformHindiToArabicNumbers::class,

    /*
     |--------------------------------------------------------------------------
     | except transform fields ( POST | GET ) from all middleware
     |--------------------------------------------------------------------------
     |
     | all none string value will be ignore by default, you can ignore fields by name (key) of POST or GET
     |
    */
    'except_from_all' => [
        // 'login'
    ],

    /*
     |--------------------------------------------------------------------------
     | except transform fields ( POST | GET ) from eastern to arabic
     |--------------------------------------------------------------------------
     |
     | all none string value will be ignore by default, you can ignore fields by name (key) of POST or GET
     |
     */
    'except_from_eastern_to_arabic' => [
        // 'mobile'
    ],

    /*
     |--------------------------------------------------------------------------
     | except transform fields ( POST | GET ) from arabic to eastern
     |--------------------------------------------------------------------------
     |
     | all none string value will be ignore by default, you can ignore fields by name (key) of POST or GET
     |
     */
    'except_from_arabic_to_eastern' => [
        // 'mobile'
    ]
];
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email yemenifree@yandex.com instead of using the issue tracker.

## Credits

- [Salah Alkhwlani][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/yemenifree/laravel-arabic-numbers-middleware.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/yemenifree/laravel-arabic-numbers-middleware/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/yemenifree/laravel-arabic-numbers-middleware.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/yemenifree/laravel-arabic-numbers-middleware.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/yemenifree/laravel-arabic-numbers-middleware.svg?style=flat-square
[ico-styleCi]: https://styleci.io/repos/105763061/shield?branch=master&style=flat

[link-packagist]: https://packagist.org/packages/yemenifree/laravel-arabic-numbers-middleware
[link-travis]: https://travis-ci.org/yemenifree/laravel-arabic-numbers-middleware
[link-scrutinizer]: https://scrutinizer-ci.com/g/yemenifree/laravel-arabic-numbers-middleware/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/yemenifree/laravel-arabic-numbers-middleware
[link-downloads]: https://packagist.org/packages/yemenifree/laravel-arabic-numbers-middleware
[link-author]: https://github.com/yemenifree
[link-contributors]: ../../contributors
[link-styleCi]: https://styleci.io/repos/105763061