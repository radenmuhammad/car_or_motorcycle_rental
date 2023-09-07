<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
| update_items
*/


Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::post('update_orders', [HomeController::class, 'update_orders'])->name('update');
Route::post('update_rent', [HomeController::class, 'update_rent'])->name('update_rent');
Route::post('update_items', [HomeController::class, 'update_items'])->name('update_items');
Route::post('update_the_returned_items', [HomeController::class, 'update_the_returned_items'])->name('update_the_returned_items');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');

Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');