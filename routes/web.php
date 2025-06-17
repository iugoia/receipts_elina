<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('/timeline', [\App\Http\Controllers\TimelineController::class, 'index'])->name('timeline');
Route::get('receipt/{receipt}', [\App\Http\Controllers\RecipeController::class, 'show'])->name('receipt.show');
Route::get('/cuisine/search', [\App\Http\Controllers\RecipeController::class, 'restaurants'])->name('restaurants.search');
Route::get('/cuisine/{cuisine}', [\App\Http\Controllers\Commons\CuisineController::class, 'show'])->name('cuisine.show');
Route::get('menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');


Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\RegisterController::class, 'index'])->name('register');
    Route::get('login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
});

Route::get('map', [\App\Http\Controllers\MapController::class, 'index'])->name('map');

Route::get('/profile/{user}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

Route::get('catalog', [\App\Http\Controllers\Commons\CatalogController::class, 'index'])->name('catalog');

Route::middleware('auth')->group(function () {
    Route::prefix('receipts')->group(function () {
        Route::get('', [\App\Http\Controllers\User\ReceiptController::class, 'index'])->name('user.receipts.index');
        Route::get('create', [\App\Http\Controllers\User\ReceiptController::class, 'create'])->name('user.receipts.create');
        Route::post('image-upload', [\App\Http\Controllers\FileUpdloadController::class, 'upload'])->name('user.receipts.image-upload');
        Route::get('favorite', [\App\Http\Controllers\User\FavoriteController::class, 'index'])->name('user.receipts.favorite.index');
        Route::get('comments', [\App\Http\Controllers\User\CommentController::class, 'index'])->name('user.comments.index');
        Route::get('comments/{comment}', [\App\Http\Controllers\User\CommentController::class, 'edit'])->name('user.comments.edit');
        Route::get('{receipt}', [\App\Http\Controllers\User\ReceiptController::class, 'edit'])->name('user.receipts.edit');
    });

    Route::prefix('user/menu')->group(function () {
        Route::get('', [\App\Http\Controllers\MenuController::class, 'show'])->name('user.menus.index');
    });

    Route::prefix('chats')->group(function () {
        Route::get('', [\App\Http\Controllers\Commons\ChatController::class, 'index']);
        Route::get('{chat}', [\App\Http\Controllers\Commons\ChatController::class, 'show'])->name('chats.show');
        Route::get('{chat}/messages', [\App\Http\Controllers\Commons\ChatController::class, 'messages']);
        Route::post('{chat}/messages', [\App\Http\Controllers\Commons\ChatController::class, 'sendMessage']);
        Route::post('{chat}/read', [\App\Http\Controllers\Commons\ChatController::class, 'markAsRead']);
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::prefix('receipts')->group(function () {
            Route::get('', [\App\Http\Controllers\Admin\AdminReceiptController::class, 'index'])->name('admin.receipts.index');
            Route::get('{receipt}', [\App\Http\Controllers\Admin\AdminReceiptController::class, 'show'])->name('admin.receipts.show');
        });

        Route::prefix('bids')->group(function () {
            Route::get('', [\App\Http\Controllers\Admin\AdminReceiptController::class, 'bids'])->name('admin.bids.index');
        });

        Route::prefix('questions')->group(function () {
            Route::get('', [\App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('admin.questions.index');
        });

        Route::prefix('comments')->group(function () {
            Route::get('', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('admin.comments.index');
        });
    });
});
