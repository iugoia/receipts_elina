<?php

use Illuminate\Support\Facades\Route;

Route::get('periods', [\App\Http\Controllers\TimelineController::class, 'periods']);
Route::get('recipes', [\App\Http\Controllers\RecipeController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::post('register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register.store');
    Route::post('login', [\App\Http\Controllers\LoginController::class, '__invoke'])->name('login.store');
});

Route::post('menu/search', [\App\Http\Controllers\MenuController::class, 'search'])->name('menu.search');

Route::post('question', [\App\Http\Controllers\Commons\QuestionController::class, 'store'])->name('question.store');

Route::middleware('auth')->group(function () {
    Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    Route::prefix('menu')->group(function () {
        Route::post('', [\App\Http\Controllers\MenuController::class, 'store'])->name('menu.store');
        Route::post('{menu}', [\App\Http\Controllers\MenuController::class, 'download'])->name('menu.download');
    });
    Route::prefix('receipts')->group(function () {
        Route::post('calories', [\App\Http\Controllers\User\ReceiptController::class, 'calories'])->name('receipt.calories.index');
        Route::post('', [\App\Http\Controllers\User\ReceiptController::class, 'store'])->name('user.receipt.store');
        Route::prefix('{receipt}')->group(function () {
            Route::put('', [\App\Http\Controllers\User\ReceiptController::class, 'update'])->name('user.receipt.update');
            Route::delete('', [\App\Http\Controllers\User\ReceiptController::class, 'delete'])->name('user.receipt.delete');
            Route::post('favorite', [\App\Http\Controllers\User\FavoriteController::class, 'store'])->name('user.receipt.favorite.store');
            Route::post('comment', [\App\Http\Controllers\User\CommentController::class, 'store'])->name('user.receipt.comment.store');
        });
        Route::put('comment/{comment}', [\App\Http\Controllers\User\CommentController::class, 'update'])->name('user.receipt.comment.update');
        Route::delete('comment/{comment}', [\App\Http\Controllers\User\CommentController::class, 'destroy'])->name('user.receipt.comment.delete');
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::prefix('receipts')->group(function () {
            Route::put('{receipt}', [\App\Http\Controllers\Admin\AdminReceiptController::class, 'update'])->name('admin.receipt.update');
            Route::delete('{receipt}', [\App\Http\Controllers\Admin\AdminReceiptController::class, 'delete'])->name('admin.receipt.delete');
        });

        Route::prefix('questions')->group(function () {
            Route::put('{question}', [\App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('admin.question.update');
        });

        Route::prefix('comments')->group(function () {
            Route::put('{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'update'])->name('admin.comment.update');
        });
    });
});
