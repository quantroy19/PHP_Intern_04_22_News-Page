<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Client\PostController as ClientPostController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    return redirect()->route('client.home');
});

Route::get('change-language/{language}', [LanguageController::class, 'changeLanguage'])->name('change-language');

Auth::routes();

Route::name('client.')
    ->group(function () {
        Route::get('/home', [ClientPostController::class, 'homepage'])->name('home');
    });

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('category', AdminCategoryController::class);
        Route::resource('post', AdminPostController::class);
    });
