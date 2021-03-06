<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function(){
    return str_random(32);
});

$router->get('/users', 'UserController@index');

$router->get('/user/{username}', 'UserController@getUsername');

$router->put('/user/{username}', 'UserController@put');

$router->post("/register", "AuthController@register");

$router->post("/login", "AuthController@login");

$router->post("/logout", "AuthController@logout");
