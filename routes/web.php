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

Route::get('/', 'HomeController@index')->middleware('auth')->name('home');
Route::get('/download', 'HomeController@storyDownload')->middleware('auth')->name('storyDownload');
Route::get('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/login', 'AuthController@checkLogin')->name('checkLogin');
Route::get('/register', 'AuthController@register')->name('register');
Route::post('/register', 'AuthController@createUser')->name('createUser');