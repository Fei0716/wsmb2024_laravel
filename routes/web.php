<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::get('/register' , [AuthController::class,'registerPage'])->name('registerPage');
Route::post('/register' , [AuthController::class,'register'])->name('register');
Route::get('/login' , [AuthController::class,'loginPage'])->name('loginPage');
Route::post('/login' , [AuthController::class,'login'])->name('login');

Route::get('/gallery' , [GalleryController::class,'index'])->name('gallery.index');
Route::get('/users' , [UserController::class,'index'])->name('users.index');

