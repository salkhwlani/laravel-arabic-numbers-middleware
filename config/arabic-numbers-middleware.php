<?php


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
     | list of middleware they will resisted for all requests automatic by package
     |--------------------------------------------------------------------------
     |
     |  Supported Middleware: "arabic-to-eastern", "eastern-to-arabic"
     */
    'auto_middleware' => Yemenifree\LaravelArabicNumbersMiddleware\Middleware\TransformEasternToArabicNumbers::class,

    /*
     |--------------------------------------------------------------------------
     | except fields ( POST | GET ) to ignore transform from all middleware
     |--------------------------------------------------------------------------
     |
     | all none string will be ignore, you can ignore fields by name (key) of POST or GET
     |
    */
    'except_from_all' => [
        // 'login'
    ],

    /*
     |--------------------------------------------------------------------------
     | except fields ( POST | GET ) to ignore transform from eastern to arabic
     |--------------------------------------------------------------------------
     |
     | all none string will be ignore, you can ignore fields by name (key) of POST or GET
     |
     */
    'except_from_eastern_to_arabic' => [
        // 'mobile'
    ],

    /*
     |--------------------------------------------------------------------------
     | except fields ( POST | GET ) to ignore transform from arabic to eastern
     |--------------------------------------------------------------------------
     |
     | all none string will be ignore, you can ignore fields by name (key) of POST or GET
     |
     */
    'except_from_arabic_to_eastern' => [
        // 'mobile'
    ],
];
