<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CarouselController;

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

// Public Carousel Route (for homepage)
Route::get('/carousel/active', [CarouselController::class, 'getActiveSlides'])->name('carousel.active');

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

// Class Management Routes
Route::group(['prefix' => 'classes', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\ClassController::class, 'index'])->middleware('role:admin,teacher');
    Route::post('/', [App\Http\Controllers\ClassController::class, 'store'])->middleware('role:admin');
    Route::get('/{class}', [App\Http\Controllers\ClassController::class, 'show'])->middleware('role:admin,teacher');
    Route::put('/{class}', [App\Http\Controllers\ClassController::class, 'update'])->middleware('role:admin');
    Route::delete('/{class}', [App\Http\Controllers\ClassController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{class}/students', [App\Http\Controllers\ClassController::class, 'getStudents'])->middleware('role:admin,teacher');
    Route::get('/{class}/subjects', [App\Http\Controllers\ClassController::class, 'getSubjects'])->middleware('role:admin,teacher');
    Route::get('/{class}/statistics', [App\Http\Controllers\ClassController::class, 'getStatistics'])->middleware('role:admin,teacher');
});

// Subject Management Routes
Route::group(['prefix' => 'subjects', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\SubjectController::class, 'index'])->middleware('role:admin,teacher');
    Route::post('/', [App\Http\Controllers\SubjectController::class, 'store'])->middleware('role:admin');
    Route::get('/{subject}', [App\Http\Controllers\SubjectController::class, 'show'])->middleware('role:admin,teacher');
    Route::put('/{subject}', [App\Http\Controllers\SubjectController::class, 'update'])->middleware('role:admin');
    Route::delete('/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{subject}/classes', [App\Http\Controllers\SubjectController::class, 'getClasses'])->middleware('role:admin,teacher');
    Route::get('/{subject}/teachers', [App\Http\Controllers\SubjectController::class, 'getTeachers'])->middleware('role:admin,teacher');
});

// Teacher Management Routes
Route::group(['prefix' => 'teachers', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\TeacherController::class, 'index'])->middleware('role:admin');
    Route::post('/', [App\Http\Controllers\TeacherController::class, 'store'])->middleware('role:admin');
    Route::get('/{teacher}', [App\Http\Controllers\TeacherController::class, 'show'])->middleware('role:admin,teacher');
    Route::put('/{teacher}', [App\Http\Controllers\TeacherController::class, 'update'])->middleware('role:admin');
    Route::delete('/{teacher}', [App\Http\Controllers\TeacherController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{teacher}/classes', [App\Http\Controllers\TeacherController::class, 'getClasses'])->middleware('role:admin,teacher');
    Route::get('/{teacher}/subjects', [App\Http\Controllers\TeacherController::class, 'getSubjects'])->middleware('role:admin,teacher');
    Route::get('/{teacher}/performance', [App\Http\Controllers\TeacherController::class, 'getPerformance'])->middleware('role:admin,teacher');
});

// Timetable Management Routes (Basic structure for future implementation)
Route::group(['prefix' => 'timetable', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Timetable management routes - to be implemented']);
    });
});

// Exam Management Routes
Route::group(['prefix' => 'exams', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\ExamController::class, 'index'])->middleware('role:admin,teacher');
    Route::post('/', [App\Http\Controllers\ExamController::class, 'store'])->middleware('role:admin,teacher');
    Route::get('/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->middleware('role:admin,teacher');
    Route::put('/{exam}', [App\Http\Controllers\ExamController::class, 'update'])->middleware('role:admin,teacher');
    Route::delete('/{exam}', [App\Http\Controllers\ExamController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{exam}/results', [App\Http\Controllers\ExamController::class, 'getResults'])->middleware('role:admin,teacher');
    Route::post('/{exam}/results', [App\Http\Controllers\ExamController::class, 'storeResults'])->middleware('role:admin,teacher');
    Route::get('/{exam}/statistics', [App\Http\Controllers\ExamController::class, 'getStatistics'])->middleware('role:admin,teacher');
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

// Parent Management Routes
Route::group(['prefix' => 'parents', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\ParentController::class, 'index'])->middleware('role:admin');
    Route::post('/', [App\Http\Controllers\ParentController::class, 'store'])->middleware('role:admin');
    Route::get('/{parent}', [App\Http\Controllers\ParentController::class, 'show'])->middleware('role:admin,parent');
    Route::put('/{parent}', [App\Http\Controllers\ParentController::class, 'update'])->middleware('role:admin');
    Route::delete('/{parent}', [App\Http\Controllers\ParentController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{parent}/children', [App\Http\Controllers\ParentController::class, 'getChildren'])->middleware('role:admin,parent');
    Route::post('/{parent}/assign-students', [App\Http\Controllers\ParentController::class, 'assignStudents'])->middleware('role:admin');
    Route::get('/{parent}/children/{student}/performance', [App\Http\Controllers\ParentController::class, 'getChildPerformance'])->middleware('role:admin,parent');
});

// Reports Routes
Route::group(['prefix' => 'reports', 'middleware' => 'auth:api'], function () {
    Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->middleware('role:admin,teacher');
    Route::get('/academic-performance', [App\Http\Controllers\ReportController::class, 'academicPerformance'])->middleware('role:admin,teacher');
    Route::get('/attendance', [App\Http\Controllers\ReportController::class, 'attendance'])->middleware('role:admin,teacher');
    Route::get('/student-analytics', [App\Http\Controllers\ReportController::class, 'studentAnalytics'])->middleware('role:admin,teacher');
    Route::get('/teacher-performance', [App\Http\Controllers\ReportController::class, 'teacherPerformance'])->middleware('role:admin');
    Route::get('/class-performance', [App\Http\Controllers\ReportController::class, 'classPerformance'])->middleware('role:admin,teacher');
    Route::post('/export', [App\Http\Controllers\ReportController::class, 'export'])->middleware('role:admin,teacher');
    Route::get('/dashboard/data', [App\Http\Controllers\ReportController::class, 'dashboard'])->middleware('role:admin,teacher');
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
