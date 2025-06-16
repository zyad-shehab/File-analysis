<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\auth\logincontroller;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomePageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



Route::delete('/destroy/{id}', [DocumentController::class, 'destroy'])->middleware('authuser')->name('documents.destroy');
route::get('/',[logincontroller::class,'login'])->name('login');
route::post('/',[logincontroller::class,'authenticate'])->name('authenticate');
Route::get('/logout', [logincontroller::class, 'logout'])->middleware('authuser')->name('logout');
Route::get('uploadform', [DocumentController::class, 'uploadForm'])->middleware('authuser')->name('documents.form');
Route::post('upload', [DocumentController::class, 'upload'])->middleware('authuser')->name('documents.upload');
Route::get('/documents', [DocumentController::class, 'list'])->middleware('authuser')->name('documents.list');
Route::get('/documents/search', [DocumentController::class, 'search'])->middleware('authuser')->name('documents.search');
Route::get('/documents/statistics', [DocumentController::class, 'statistics'])->middleware('authuser')->name('documents.statistics');
Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->middleware('authuser')->name('documents.download');
