<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportDataController;

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

/* Route::get('/', function () {
    // return view('welcome');
    return view('index');
}); */


Route::get('/', [App\Http\Controllers\ImportDataController::class, 'index']);
Route::post('import-and-encrypt', [App\Http\Controllers\ImportDataController::class, 'import'])->name('import-and-encrypt');
Route::post('change-encryption-key', [App\Http\Controllers\ImportDataController::class, 'changeKey'])->name('change-encryption-key');