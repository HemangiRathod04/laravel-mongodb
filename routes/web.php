<?php

use Illuminate\Support\Facades\Route;
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

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
Route::get('users/add', [UserController::class, 'create'])->name('users.create');
Route::post('users/add', [UserController::class, 'store']);
Route::get('/', [UserController::class, 'list'])->name('users.list');
Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::post('users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('users/deleteSelectedUsers', [UserController::class, 'deleteSelectedUserIds'])->name('users.deleteSelectedUserIds');
Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');

Route::post('users/filter', [UserController::class, 'filter'])->name('users.filter');
