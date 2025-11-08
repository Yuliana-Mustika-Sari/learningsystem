<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\CourseCountroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\ListCourse;
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

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/listcourse', [ListCourse::class, 'index'])->name('listcourse');
        Route::get('/listcourse/{courseId}/pay', [ListCourse::class, 'create'])->name('listcourse.pay');
        Route::post('/listcourse/{courseId}/pay', [ListCourse::class, 'store'])->name('listcourse.pay.store');
        Route::get('/listcourse/{id}', [CourseController::class, 'show'])->name('course.show');
        Route::post('/listcourse/webhook', [ListCourse::class, 'webhook'])->name('listcourse.webhook');
    });

Route::middleware(['auth'])->group(function () {
    Route::prefix('courses/{course}')->name('courses.')->group(function () {
        Route::middleware(['permission:discussion_management|discussion_student'])->group(function () {
            Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
            Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
        });
        Route::middleware(['permission:discussion_management'])->group(function () {
            Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
        });
    });
});
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/courses', [ListCourse::class, 'index'])->name('student.listcourse');
    Route::get('/student/courses/{id}', [ListCourse::class, 'show'])->name('student.course.show');
    Route::get('/student/courses/{course}/assignments', [\App\Http\Controllers\Student\AssignmentController::class, 'index'])
        ->name('student.assignments.index');

    Route::get('/student/payment/{course}/confirm', [PaymentController::class, 'confirm'])->name('student.payment.confirm');
    Route::post('/student/payment/{course}/process', [PaymentController::class, 'process'])->name('student.payment.process');
    Route::get('/student/payment/{payment}/waiting', [PaymentController::class, 'waiting'])->name('student.payment.waiting');
    Route::get('/student/payment/{payment}/status', [PaymentController::class, 'checkStatus'])->name('student.payment.status');
    Route::get('/payment/success/{payment}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/student/payment/{payment}/success', [PaymentController::class, 'success'])->name('student.payment.success');
});

// Accept both GET (gateway redirect) and POST (server webhook)
Route::match(['get','post'], '/webhook/payment', [PaymentController::class, 'callback'])->name('payment.webhook');
require __DIR__ . '/auth.php';
