<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('posts', 'PostController@index')->name('posts');
Route::get('posts/create', 'PostController@create')->name('create.get');
Route::post('posts', 'PostController@store')->name('create.post');
Route::delete('posts/{post}', 'PostController@destroy');
