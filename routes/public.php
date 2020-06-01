<?php

use Illuminate\Support\Facades\Route;

Route::get('/users', 'UserController@getUsers');
Route::get('/users/{id}', 'UserController@getUser');
Route::get('/users/{id}/posts', 'UserController@getUserPosts');
Route::get('/posts/{id}/comments', 'PostController@getPostCommnents');
Route::get('/posts', 'PostController@getPosts');
Route::get('/users/posts', 'UserController@getUsersPosts');
