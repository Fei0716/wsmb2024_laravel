<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
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
Route::middleware('guest')->group(function(){
    Route::get('/' , [AuthController::class,'loginPage'])->name('loginPage');
    Route::get('/login' , [AuthController::class,'loginPage'])->name('loginPage');
    Route::post('/login' , [AuthController::class,'login'])->name('login');

    Route::get('/register' , [AuthController::class,'registerPage'])->name('registerPage');
    Route::post('/register' , [AuthController::class,'register'])->name('register');
});

Route::middleware('auth')->group(function(){
    Route::post('/logout' , [AuthController::class,'logout'])->name('logout');

    Route::get('/gallery' , [GalleryController::class,'index'])->name('gallery.index');
    Route::get('/gallery/{album}' , [GalleryController::class,'show'])->name('gallery.show');

//    Route::get('/albums' , [AlbumController::class,'index'])->name('albums.index');
//    Route::get('/albums/{album}' , [AlbumController::class,'show'])->name('albums.show');
//    Route::post('/albums' , [AlbumController::class,'store'])->name('albums.store');
//    Route::delete('/albums/{album}' , [AlbumController::class, 'destroy'])->name('albums.destroy');
//    Route::put('/albums/{album}' , [AlbumController::class,'update'])->name('albums.update');
    Route::resource('/albums' , AlbumController::class)->except('create' ,'edit');

    Route::delete('/images/{image}' , [ImageController::class, 'destroy'])->name('images.destroy');
    Route::get('/images' , [ImageController::class,'index'])->name('images.index');
    Route::post('/images' , [ImageController::class,'store'])->name('images.store');

    Route::get('/users' , [UserController::class,'index'])->name('users.index');
    Route::delete('/users/{user}' , [UserController::class,'destroy'])->name('users.destroy');


    Route::post('/likes' , [LikeController::class , 'store'])->name('likes.store');
});



