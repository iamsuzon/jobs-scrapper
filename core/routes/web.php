<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeywordsController;
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

Route::get('/admin-login', [LoginController::class, 'customLoginForm'])->name('admin.login');
Route::post('/admin-login', [LoginController::class, 'customLogin']);

Auth::routes(['register' => false]); // Disable register route

Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::get('/scrap-it', [HomeController::class, 'scrapIt'])->name('scrap.it');
Route::get('/all-jobs', [HomeController::class, 'allJobs'])->name('jobs.all');
Route::post('/job/search', [HomeController::class, 'searchJob'])->name('jobs.search');
Route::post('/job/status', [HomeController::class, 'statusChangeJob'])->name('jobs.status.change');
Route::get('/all-jobs/type/{type}', [HomeController::class, 'allJobsType'])->name('jobs.all.type');

Route::get('/all-jobs/searching/left', [HomeController::class, 'searchingLeft'])->name('jobs.all.searching.left');

Route::get('/keywords', [KeywordsController::class, 'keywords'])->name('keywords');
Route::post('/keywords', [KeywordsController::class, 'keywordsUpdate']);
Route::get('/keywords/delete/{id}', [KeywordsController::class, 'keywordsDelete'])->name('keywords.delete');
Route::get('/keywords/edit/{id}', [KeywordsController::class, 'keywordsEdit'])->name('keywords.edit');
Route::post('/keywords/update', [KeywordsController::class, 'keywordsUpdate']);
Route::get('/keywords/status/{id}', [KeywordsController::class, 'keywordsStatus'])->name('keywords.status');
