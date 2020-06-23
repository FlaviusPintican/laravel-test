<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/users', 'UserController@getUsers');
Route::get('/users/{user_id}', 'UserController@getUser');
Route::get('/images/random', 'ImageController@getRandomImage');
Route::get('/images/{image_id}', 'ImageController@getImage');
Route::get('/images', 'ImageController@getImages');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/users', 'UserController@addUser');
    Route::put('/users/{user_id}', 'UserController@editUser');
    Route::delete('/users/{user_id}', 'UserController@deleteUser');
    Route::delete('/users/comments', 'UserController@addComment');
    Route::put('/images/{image_id}', 'ImageController@editImage');
    Route::delete('/images', 'ImageController@deleteImage');
    Route::post('/images', 'ImageController@addImage');
    Route::post('/logout', 'UserController@logout');
});
