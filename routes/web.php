<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Instructor\CourseCountroller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware(['auth', 'permission:dashboard_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
    });
Route::middleware(['auth', 'permission:user_management'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('permission:user_management')->group(function () {
        Route::resource('/users', UserController::class);
    });
});
Route::middleware(['auth', 'permission:course_management'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('permission:course_management')->group(function () {
        Route::resource('/courses', CourseController::class);
    });
});
Route::middleware(['auth', 'permission:course_instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::middleware('permission:course_instructor')->group(function () {
        Route::resource('/courses', CourseCountroller::class);
    });
});
Route::middleware(['auth', 'permission:create_course'])->group(function () {
    Route::get('/courses/create', function () {
        return "Form Create Course";
    })->name('courses.create');
});

Route::middleware(['auth', 'permission:grade_assignment'])->group(function () {
    Route::get('/assignments/{id}/grade', function ($id) {
        return "Form Grade Assignment $id";
    });
});
Route::middleware(['auth', 'permission:view_course'])->group(function () {
    Route::get('/courses', function () {
        return "List Courses";
    })->name('courses.index');
});

Route::middleware(['auth', 'permission:submit_assignment'])->group(function () {
    Route::post('/assignments/{id}/submit', function ($id) {
        return "Submit Assignment $id";
    });
});
Route::middleware(['auth', 'permission:submit_assignment'])->group(function () {
    Route::get('/assignment', function () {
        return "Submit Assignment";
    })->name('assignment.index');
});


require __DIR__.'/auth.php';
