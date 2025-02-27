<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;

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

Route::resource('', 'FolderController');
//Route::resource('', 'FileController');
Route::resource('files', 'FileController');
Route::resource('folders', 'FolderController');

Route::post('store', [FileController::class, 'store']);
Route::get('files/{id}', 'FileController@show')->name('files.show');