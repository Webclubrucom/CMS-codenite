<?php

use System\Core\Helpers\Route;

return [
	Route::get('/login', 'LoginController@index'),
	Route::post('/login/auth', 'LoginController@auth'),
	Route::get('/register', 'RegisterController@index'),
	Route::post('/register/auth', 'RegisterController@auth'),
	Route::get('/', 'HomeController@index'),
	Route::get('/users', 'UsersController@index'),
];