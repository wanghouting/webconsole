<?php

return [
    /*
    |--------------------------------------------------------------------------
    |  Disable login (don't ask for credentials, be careful)
    |  Example:  true;
    |--------------------------------------------------------------------------
    */
    'no_login'  =>  env("WEB_CONSOLE_NO_LOGIN",false),
    /*
    |--------------------------------------------------------------------------
    | Multi-user credentials
    | Example:  ['user1' => 'password1', 'user2' => 'password2'];
    |--------------------------------------------------------------------------
    */
    'account'  => [
        env("WEB_CONSOLE_DEFAULT_USER",'admin') =>  env("WEB_CONSOLE_DEFAULT_PASSWORD",'admin')
    ],

    /*
    |--------------------------------------------------------------------------
    | Password hash algorithm (password must be hashed)
    | Example: 'md5';
    |          'sha256';
    |--------------------------------------------------------------------------
    */
    'password_hash_algorithm'  =>  env("WEB_CONSOLE_PASSWORD_HASH_ALGORITHM",''),

    /*
    |--------------------------------------------------------------------------
    | Home directory
    | Example:  '/tmp';
    |--------------------------------------------------------------------------
    */
    'home_dir'  =>  env("WEB_CONSOLE_HOME_DIR",''),
    /*
    |--------------------------------------------------------------------------
    | route prefix
    |--------------------------------------------------------------------------
    */
    'route_prefix' => env("WEB_CONSOLE_ROUTE_PREFIX",'admin'),
    /*
    |--------------------------------------------------------------------------
    | route middleware
    |--------------------------------------------------------------------------
    */
    'route_middleware' => [

    ],
    /*
    |--------------------------------------------------------------------------
    | route name
    |--------------------------------------------------------------------------
    */

    "route_name" => env("WEB_CONSOLE_ROUTE_PATH",'console'),




];
