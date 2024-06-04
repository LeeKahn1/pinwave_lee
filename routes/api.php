<?php

use App\Http\Controllers\Mobile\MobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('testing', [MobileController::class, 'testing']);
Route::post('sign-up', [MobileController::class, 'register']);
Route::post('sign-in', [MobileController::class, 'login']);
Route::get('home-pins', [MobileController::class, 'getAllPin']);
Route::get('search-pins', [MobileController::class, 'getSearchPin']);

Route::middleware(['auth-api'])->group( function () {
    Route::get('user', [MobileController::class, 'getUser']);
    Route::put('sign-out', [MobileController::class, 'logout']);
    Route::get('pins/{id}', [MobileController::class, 'getPinDetails']);
    Route::post('pins/save-or-remove/', [MobileController::class, 'saveOrRemovePinInAlbum']);
    Route::post('create-pins', [MobileController::class, 'createPin']);
    Route::post('create-comments', [MobileController::class, 'postCommentPin']);
    Route::put('follows/{id}', [MobileController::class, 'putFollowing']);
    Route::put('reports/{id}', [MobileController::class, 'putReport']);
    Route::put('likes/{id}', [MobileController::class, 'putLikePin']);
    Route::get('notifications', [MobileController::class, 'getAllNotification']);
    Route::get('unread-notifications', [MobileController::class, 'getUnreadNotificationsCount']);
    Route::put('read-notifications/{id}', [MobileController::class, 'putReadNotification']);
    Route::put('read-all-notifications', [MobileController::class, 'putReadAllNotification']);
    Route::post('change-profiles', [MobileController::class, 'updateProfile']);
    Route::put('change-passwords', [MobileController::class, 'updatePassword']);
    Route::get('accounts', [MobileController::class, 'account']);
    Route::get('pins/albums/{id}', [MobileController::class, 'pinAlbumPhotoList']);
    Route::get('albums', [MobileController::class, 'albumListName']);
    Route::get('albums/pins', [MobileController::class, 'pinAlbumThumbnailList']);
    Route::post('albums/add', [MobileController::class, 'addAlbumName']);
});
