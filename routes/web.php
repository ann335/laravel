<?php

use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(auth()->check() ? 'wishes.index' : 'login'));

Route::middleware('auth')->group(function () {
    Route::get('/wishes', [WishController::class, 'index'])->name('wishes.index');
    Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store');
    Route::get('/wishes/search', [WishController::class, 'search'])->name('wishes.search');
    Route::put('/wishes/{wish}', [WishController::class, 'update'])->name('wishes.update');
    Route::delete('/wishes/{wish}', [WishController::class, 'destroy'])->name('wishes.destroy');
});

require __DIR__.'/settings.php';
