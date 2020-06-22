<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/users', 'UserController@addUser');
    Route::get('/users', 'UserController@getUsers');
    Route::get('/users/{user_id}', 'UserController@getUser');
    Route::put('/users/{user_id}', 'UserController@editUser');
    Route::delete('/users/{user_id}', 'UserController@deleteUser');
    Route::delete('/users/comments', 'UserController@addComment');
    Route::get('/images/random', 'ImageController@getRandomImage');
    Route::put('/images/{image_id}', 'ImageController@editImage');
    Route::get('/images/{image_id}', 'ImageController@getImage');
    Route::post('/images', 'ImageController@addImage');
    Route::post('/logout', 'UserController@logout');
});
