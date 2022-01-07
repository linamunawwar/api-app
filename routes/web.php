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
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//google
Route::get('/google', [App\Http\Controllers\GoogleController::class, 'index'])->name('home');