<?php
/**
 * Created by PhpStorm.
 * User: wanghouting
 * Date: 2019-06-14
 * Time: 13:40
 */
use Illuminate\Routing\Router;

Route::group([
    'namespace' => "WebConsole\\Extension\\Controllers",
    'prefix' => config('webconsole.route_prefix'),
    'middleware' => ['web', 'admin']
], function (Router $router) {
    $router->get(config('webconsole.route_name'), 'WebConsoleController@index');
});
