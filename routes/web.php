<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ReportController;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/search', [SearchController::class, 'search']);
Route::get('/user/{username}', [ProfileController::class, 'userProfile'])->name('user.profile');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('unfollow');
});

Route::middleware('auth')->group(function () {
    Route::get('/pins', [PinController::class, 'index'])->name('pins.index');
    Route::get('/pins/create', [PinController::class, 'create'])->name('pins.create');
    Route::post('/pins', [PinController::class, 'store'])->name('pins.store');
    Route::get('/pins/{pin}', [PinController::class, 'show'])->name('pins.show');
    Route::get('/pins/{pin}/edit', [PinController::class, 'edit'])->name('pins.edit');
    Route::put('/pins/{pin}', [PinController::class, 'update'])->name('pins.update');
    Route::delete('/pins/{pin}', [PinController::class, 'destroy'])->name('pins.destroy');
    Route::post('/pins/{pin}/like', [LikeController::class, 'store'])->name('like');
    Route::post('/pins/{pin}/unlike', [LikeController::class, 'destroy'])->name('unlike');
    Route::post('/pins/{pin}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/reports', [ReportController::class, 'store']);
    Route::delete('/reports/{id}', [ReportController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
    Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create');
    Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
    Route::get('/albums/{album}', [AlbumController::class, 'show'])->name('albums.show');
    Route::get('/albums/{album}/edit', [AlbumController::class, 'edit'])->name('albums.edit');
    Route::put('/albums/{album}', [AlbumController::class, 'update'])->name('albums.update');
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    Route::post('/albums/{album}/addPin', [AlbumController::class, 'addPin'])->name('albums.addPin');
    Route::post('/albums/{album}/removePin', [AlbumController::class, 'removePin'])->name('albums.removePin');
    Route::post('/album/{albumId}/save', [AlbumController::class, 'saveToAlbum'])->name('saveToAlbum');
});

require __DIR__ . '/auth.php';
