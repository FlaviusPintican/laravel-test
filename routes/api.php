<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/users', 'UserController@getUsers');
Route::put('/users/{user_id}', 'UserController@editUser');
Route::delete('/users/{user_id}', 'UserController@deleteUser');
Route::post('/logout', 'UserController@logout');

