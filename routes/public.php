<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('/users', 'UserController@addUser');
Route::post('/login', 'UserController@login');
