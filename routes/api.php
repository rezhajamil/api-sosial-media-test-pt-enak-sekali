<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
    });

    Route::prefix('follow')->name('follow.')->group(function () {
        Route::get('/followers', [FollowController::class, 'getFollowers'])->name('get_followers');
        Route::get('/following', [FollowController::class, 'getFollowing'])->name('get_following');
        Route::put('/toggle/{user}', [FollowController::class, 'toggleFollow'])->name('toggle');
    });

    Route::prefix('post')->name('post.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::post('/create', [PostController::class, 'create'])->name('create');
        Route::put('/edit/{post}', [PostController::class, 'edit'])->name('edit');

        Route::put('/like/toggle/{post}', [PostLikeController::class, 'toggleLike'])->name('toggle');
        Route::get('/like/my_likes', [PostLikeController::class, 'getMyLikes'])->name('my_likes');
        Route::post('/comment/create/{post}', [PostCommentController::class, 'create'])->name('comment.create');
        Route::delete('/comment/delete/{comment}', [PostCommentController::class, 'destroy'])->name('comment.delete');
    });
});


Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
