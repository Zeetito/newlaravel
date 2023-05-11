<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

//User related
Route::get('/admins-only', function(){
    return "Only Admins Can see this page";
})->middleware('can:VisitAdminPages');

Route::get('/', [UserController::class,"ShowCorrectHomepage"])->name('login');
Route::post('/register', [UserController::class,"register"])->middleware('guest');
Route::post('/login', [UserController::class,"login"])->middleware('guest');
Route::post('/logout', [UserController::class,"logout"])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar',[UserController::class,"ShowAvatarForm"])->middleware('auth');
Route::post('/manage-avatar',[UserController::class,"storeAvatar"])->middleware('auth');

// Blog Post realated
Route::get('/create-post',[PostController::class,"ShowCreateForm"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}',[PostController::class,"VeiwSinglePost"])->middleware('mustBeLoggedIn');
Route::delete('/post/{post}',[PostController::class,"delete"])->middleware('can:delete,post');
Route::post('/create-post',[PostController::class,"StoreNewPost"])->middleware('mustBeLoggedIn');
Route::get('post/{post}/edit',[PostController::class,"ShowEditForm"])->middleware('can:update,post');
Route::put('post/{post}',[PostController::class,"actuallyUpdate"])->middleware('can:update,post');

//Profile Related
Route::get('/profile/{user:username}',[UserController::class,"profile"])->middleware('mustBeLoggedIn');