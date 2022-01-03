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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
Route::post('/user/create', [App\Http\Controllers\UserController::class, 'store'])->name('user.create');
Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
Route::post('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');

Route::get('/user/edit_pass/{id}', [App\Http\Controllers\UserController::class, 'editPass'])->name('user.edit_pass');
Route::post('/user/edit_pass/{id}', [App\Http\Controllers\UserController::class, 'updatePass'])->name('user.update_pass');
Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');


/* Social Media */
Route::get('/twitter', [App\Http\Controllers\TwitterController::class, 'index'])->name('twitter');

// Authorization Twitter
Route::get('/media/twitter', [App\Http\Controllers\TwitterController::class, 'twitter_connect'])->name('media.twitter');
Route::get('/twitter/callback', [App\Http\Controllers\TwitterController::class, 'twitter_callback'])->name('media.callback');

Route::post('/twitter/search', [App\Http\Controllers\TwitterController::class, 'fetch_twitter'])->name('twitter.search');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
