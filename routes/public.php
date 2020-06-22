<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('/login', 'UserController@login')->name('login');
