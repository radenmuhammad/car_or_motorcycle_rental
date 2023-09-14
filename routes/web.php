<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ExportExcelController;
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
Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('update_orders', [HomeController::class, 'update_orders'])->name('update_orders');
Route::post('update_rents', [HomeController::class, 'update_rents'])->name('update_rents');
Route::post('update_items', [HomeController::class, 'update_items'])->name('update_items');
Route::post('update_the_returned_items', [HomeController::class, 'update_the_returned_items'])->name('update_the_returned_items');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');
Route::post('update_rents', [HomeController::class, 'update_rents'])->name('update_rents');
Route::get('create_orders_pdf', [HomeController::class, 'create_orders_pdf'])->name('create_orders_pdf');
Route::get('create_items_pdf', [HomeController::class, 'create_items_pdf'])->name('create_items_pdf');
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');

Route::controller(ExportExcelController::class)->group(function(){
    Route::get('exportItems', 'exportItems')->name('exportItems.excel');
    Route::get('exportOrders', 'exportOrders')->name('exportOrders.excel');
	Route::post('importItems','importItems')->name('importItems.excel');
	Route::post('importRents','importRents')->name('importRents.excel');

});

// importRent



