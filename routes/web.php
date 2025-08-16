<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [WebController::class, 'welcome'])->name('welcome');

// Authentication Routes (using Laravel Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // School Management Dashboard
    Route::get('/school-dashboard', [DashboardController::class, 'index'])->name('school.dashboard');
    
    // Student Management Routes
    Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('role:admin,teacher');
        Route::get('/create', [StudentController::class, 'create'])->name('create')->middleware('role:admin,teacher');
        Route::post('/', [StudentController::class, 'store'])->name('store')->middleware('role:admin,teacher');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile')->middleware('role:student');
        Route::get('/class/{classId}', [StudentController::class, 'getByClass'])->name('by-class')->middleware('role:admin,teacher');
        Route::post('/bulk-import', [StudentController::class, 'bulkImport'])->name('bulk-import')->middleware('role:admin');
        Route::get('/{id}', [StudentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('role:admin,teacher');
        Route::put('/{id}', [StudentController::class, 'update'])->name('update')->middleware('role:admin,teacher');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy')->middleware('role:admin');
    });
    
    // Attendance Management Routes
    Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('mark')->middleware('role:admin,teacher');
        Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics')->middleware('role:admin,teacher');
        Route::get('/student/{studentId}/report', [AttendanceController::class, 'studentReport'])->name('student-report');
        Route::get('/class/{classId}/report', [AttendanceController::class, 'classReport'])->name('class-report')->middleware('role:admin,teacher');
    });
    
    // Class Management Routes
    Route::group(['prefix' => 'classes', 'as' => 'classes.'], function () {
        Route::get('/', function () {
            return view('classes.index', ['message' => 'Class management - to be implemented']);
        })->name('index');
    });
    
    // Subject Management Routes
    Route::group(['prefix' => 'subjects', 'as' => 'subjects.'], function () {
        Route::get('/', function () {
            return view('subjects.index', ['message' => 'Subject management - to be implemented']);
        })->name('index');
    });
    
    // Teacher Management Routes
    Route::group(['prefix' => 'teachers', 'as' => 'teachers.'], function () {
        Route::get('/', function () {
            return view('teachers.index', ['message' => 'Teacher management - to be implemented']);
        })->name('index');
    });
    
    // Timetable Management Routes
    Route::group(['prefix' => 'timetable', 'as' => 'timetable.'], function () {
        Route::get('/', function () {
            return view('timetable.index', ['message' => 'Timetable management - to be implemented']);
        })->name('index');
    });
    
    // Exam Management Routes
    Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
        Route::get('/', function () {
            return view('exams.index', ['message' => 'Exam management - to be implemented']);
        })->name('index');
    });
    
    // Results Management Routes
    Route::group(['prefix' => 'results', 'as' => 'results.'], function () {
        Route::get('/', function () {
            return view('results.index', ['message' => 'Results management - to be implemented']);
        })->name('index');
    });
    
    // Announcements Routes
    Route::group(['prefix' => 'announcements', 'as' => 'announcements.'], function () {
        Route::get('/', function () {
            return view('announcements.index', ['message' => 'Announcement management - to be implemented']);
        })->name('index');
    });
    
    // Parent Management Routes
    Route::group(['prefix' => 'parents', 'as' => 'parents.'], function () {
        Route::get('/', function () {
            return view('parents.index', ['message' => 'Parent management - to be implemented']);
        })->name('index');
    });
    
    // Reports Routes
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', function () {
            return view('reports.index', ['message' => 'Reporting - to be implemented']);
        })->name('index');
    });
    
    // Settings/Configuration Routes
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', function () {
            return view('settings.index', ['message' => 'Settings - to be implemented']);
        })->name('index')->middleware('role:admin');
    });
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'School Management System is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

require __DIR__.'/auth.php';
