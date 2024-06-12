<?php

Route::get('/', 'LoginController@index');
Route::get('/{id:\d+}', 'LoginController@show');
Route::get('/{name}', function (string $name) {
    return new \System\Core\Http\Response("Привет, $name");
});