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

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/','ArticleController@index');

Route::get('/search','ArticleController@search')->name('search');

Route::get('/pass',function (){
    return Hash::make('1234567');
});