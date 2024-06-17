<?php

use System\Core\Router\Facades\Route;

return [
    Route::get('/', 'LoginController@index'),
    Route::get('/{id:\d+}', 'LoginController@show'),
    Route::get('/{name}/{id:\d+}', function (string $name, int $id) {
        return new \System\Core\Http\Response("Привет, $name");
    }),
];
