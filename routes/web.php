<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
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

Route::get('/', function () {
    return view('welcome');
});




Route::get('uploadform', [DocumentController::class, 'uploadForm'])->name('documents.form');
Route::post('upload', [DocumentController::class, 'upload'])->name('documents.upload');
Route::get('documents', [DocumentController::class, 'list'])->name('documents.list');
Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
Route::get('/documents/statistics', [DocumentController::class, 'statistics'])->name('documents.statistics');
Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');
