<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\CourseCountroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\StudentController;
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

// General discussions index (list all discussions across courses)
Route::middleware(['auth'])->get('/discussions', [DiscussionController::class, 'all'])
    ->name('discussions.index');

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
Route::middleware(['auth', 'permission:assignment_management'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::middleware('permission:assignment_management')->group(function () {
        Route::resource('assignments', AssignmentController::class);
    });
});
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentController::class, 'index'])->name('courses');

    // Payments: allow all students (role check done by outer middleware).
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('/payment/{courseId}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/{courseId}', [PaymentController::class, 'store'])->name('payment.store');
});
Route::middleware(['auth'])->group(function () {
    Route::prefix('courses/{course}')->name('courses.')->group(function () {

        // Semua user dengan izin diskusi (instructor & student)
        Route::middleware(['permission:discussion_management|discussion_student'])->group(function () {
            Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
            Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
        });

        // Hapus hanya boleh untuk instructor
        Route::middleware(['permission:discussion_management'])->group(function () {
            Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
        });
    });
});


require __DIR__ . '/auth.php';

// Public webhook endpoint for payment gateway
Route::post('/payment/webhook', [\App\Http\Controllers\Student\PaymentController::class, 'webhook'])
    ->name('student.payment.webhook');
