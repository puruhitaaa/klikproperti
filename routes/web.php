<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyReviewController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/services', function () {
    return Inertia::render('Services');
})->name('services');
Route::get('/about-us', function () {
    return Inertia::render('AboutUs');
})->name('about-us');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/properties/{property}/reviews', [PropertyReviewController::class, 'index'])->name('property.reviews.index');
    Route::post('/properties/{property}/reviews', [PropertyReviewController::class, 'store'])->name('property.reviews.store');
    Route::put('/properties/{property}/reviews/{review}', [PropertyReviewController::class, 'update'])->name('property.reviews.update');
    Route::delete('/properties/{property}/reviews/{review}', [PropertyReviewController::class, 'destroy'])->name('property.reviews.destroy');
});

require __DIR__.'/auth.php';
