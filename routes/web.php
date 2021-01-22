<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/members', 'App\Http\Controllers\MembersController@index')->name('members.index');
Route::post('/members', 'App\Http\Controllers\MembersController@store')->name('members.store');
Route::delete('/members', 'App\Http\Controllers\MembersController@delete')->name('members.delete');