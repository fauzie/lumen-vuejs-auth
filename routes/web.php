<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () {
        return [
            'name' => 'Simple Auth API',
            'version' => '1.0'
        ];
    });
    $router->get('/user', 'UserController@index');
    $router->put('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->get('/logout', 'AuthController@logout');
    $router->post('/exists', 'AuthController@exists');
    $router->post('/unique', 'AuthController@unique');
});

$router->get('/{any:(?!api).*$}', function () {
    return view('app');
});
