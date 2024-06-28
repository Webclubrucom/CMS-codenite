<?php

use System\Core\Router\Facades\Route;

return [
	Route::get('/', 'HomeController@index'),
	Route::get('/login', 'LoginController@index'),
	Route::post('/login/auth', 'LoginController@auth'),
];