<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
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
    return to_route('admin.login');
});

Route::post('/admin-login', [LoginController::class, 'customLogin'])->name('admin.login');

Auth::routes(['register' => false]); // Disable register route

Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::get('/scrap-it', [HomeController::class, 'scrapIt'])->name('scrap.it');
