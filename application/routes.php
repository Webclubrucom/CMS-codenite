<?php

use System\Core\Router\Facades\Route;

return [
    Route::get('/', 'HomeController@index'),
    Route::get('/login', 'LoginController@index'),
    Route::get('/{name}/{id:\d+}', function (string $name, int $id) {
        return new \System\Core\Http\Response("Привет, $name");
    }),
];
