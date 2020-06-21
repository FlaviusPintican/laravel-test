<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('/users', 'UserController@addUser');

Route::group(['middleware' => 'api'], function() {
    Route::get('/users', 'UserController@getUsers');
    Route::put('/users/{user_id}', 'UserController@editUser');
    Route::delete('/users/{user_id}', 'UserController@deleteUser');
});
