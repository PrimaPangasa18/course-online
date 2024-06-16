<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribeTransactionController;
use App\Http\Controllers\TeacherController;
use App\Models\SubscribeTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

// domain.com/details/misal:html-css/laravel10. Terrgantung kelas yang dipilih
Route::get('/details/{course/slug}', [FrontController::class, 'details'])->name('front.details');
Route::get('/category/{category/slug}', [FrontController::class, 'category'])->name('front.category');

Route::get('/pricing', [FrontController::class, 'pricing'])->name('front.pricing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute buat harus login terlebih dahulu sebelum mengakses yang lain karena ada fungsi group
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Harus login terlebih dahulu supaya bisa checkout dan membeli kursus tersebut
    Route::get('/checkout', [FrontController::class, 'checkout'])->name('front.checkout')->middleware('role:student');
    Route::get('/checkout_store', [FrontController::class, 'checkout_store'])->name('front.checkout.store')->middleware('role:student');

    // domain.com/learning/100/5 ->maksudnya learning/course ke 30/vide0 5: Belajar PHP
    Route::get('/learning/{course}/{courseVideoId} ', [FrontController::class, 'learning'])->name('front.learning')->middleware('role:student|teacher|owner');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->middleware('role:owner'); //admin.categories.index/show/delete
        Route::resource('teachers', TeacherController::class)->middleware('role:owner');
        Route::resource('courses', CourseController::class)->middleware('role:owner|teacher');
        Route::resource('subscribe_transactions', SubscribeTransactionController::class)->middleware('role:owner');


        Route::get('/add/video/{course:id}', [CourseVideoController::class, 'create'])->name('course.add_video')->middleware('role:owner|teacher');
        Route::post('/add/video/save/{course:id}', [CourseVideoController::class, 'store'])->name('course.add_video.save')->middleware('role:owner|teacher');

        Route::resource('course_videos', CourseVideoController::class)->middleware('role:owner|teacher');
    });
});

require __DIR__ . '/auth.php';
