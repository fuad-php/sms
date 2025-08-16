<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [JWTAuthController::class, 'login']);
    Route::post('register', [JWTAuthController::class, 'register']);
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [JWTAuthController::class, 'logout']);
        Route::post('refresh', [JWTAuthController::class, 'refresh']);
        Route::get('me', [JWTAuthController::class, 'me']);
        Route::put('profile', [JWTAuthController::class, 'updateProfile']);
        Route::put('change-password', [JWTAuthController::class, 'changePassword']);
    });
});

// Dashboard Routes
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
});

// Student Management Routes
Route::group(['prefix' => 'students', 'middleware' => 'auth:api'], function () {
    Route::get('/', [StudentController::class, 'index'])->middleware('role:admin,teacher');
    Route::post('/', [StudentController::class, 'store'])->middleware('role:admin,teacher');
    Route::get('/profile', [StudentController::class, 'profile'])->middleware('role:student');
    Route::get('/class/{classId}', [StudentController::class, 'getByClass'])->middleware('role:admin,teacher');
    Route::post('/bulk-import', [StudentController::class, 'bulkImport'])->middleware('role:admin');
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::put('/{id}', [StudentController::class, 'update'])->middleware('role:admin,teacher');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->middleware('role:admin');
});

// Attendance Management Routes
Route::group(['prefix' => 'attendance', 'middleware' => 'auth:api'], function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/mark', [AttendanceController::class, 'markAttendance'])->middleware('role:admin,teacher');
    Route::get('/statistics', [AttendanceController::class, 'statistics'])->middleware('role:admin,teacher');
    Route::get('/student/{studentId}/report', [AttendanceController::class, 'studentReport']);
    Route::get('/class/{classId}/report', [AttendanceController::class, 'classReport'])->middleware('role:admin,teacher');
});

// Class Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'classes', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Class management routes - to be implemented']);
    });
});

// Subject Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'subjects', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Subject management routes - to be implemented']);
    });
});

// Teacher Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'teachers', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Teacher management routes - to be implemented']);
    });
});

// Timetable Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'timetable', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Timetable management routes - to be implemented']);
    });
});

// Exam Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'exams', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Exam management routes - to be implemented']);
    });
});

// Results Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'results', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Results management routes - to be implemented']);
    });
});

// Announcements Routes
Route::group(['prefix' => 'announcements', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\AnnouncementController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\AnnouncementController::class, 'dashboard']);
    Route::post('/', [App\Http\Controllers\AnnouncementController::class, 'store'])->middleware('role:admin,teacher');
    Route::get('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show']);
    Route::put('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'update'])->middleware('role:admin,teacher');
    Route::delete('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->middleware('role:admin,teacher');
    Route::patch('/{announcement}/toggle-publish', [App\Http\Controllers\AnnouncementController::class, 'togglePublish'])->middleware('role:admin,teacher');
    Route::get('/{announcement}/download', [App\Http\Controllers\AnnouncementController::class, 'downloadAttachment']);
});

// Parent Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'parents', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Parent management routes - to be implemented']);
    });
});

// Reports Routes (Basic structure for future implementation)
Route::group(['prefix' => 'reports', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Reporting routes - to be implemented']);
    });
});

// Settings/Configuration Routes (Basic structure for future implementation)
Route::group(['prefix' => 'settings', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Settings routes - to be implemented']);
    })->middleware('role:admin');
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'School Management System API is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});
