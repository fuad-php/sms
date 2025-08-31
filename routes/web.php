<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ContactController;
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
Route::get('/', [WebController::class, 'welcome'])->name('home')->middleware('maintenance');
// Public Announcements Routes (accessible to everyone)
Route::get('/announcement', [AnnouncementController::class, 'publicAnnouncements'])->name('announcements.public');
Route::get('/announcement/{announcement}', [AnnouncementController::class, 'publicShow'])->name('announcements.public.show');
Route::get('/announcement/{announcement}/download', [AnnouncementController::class, 'publicDownloadAttachment'])->name('announcements.public.download');



// Contact form routes (public)
Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'store'])->name('store');
});

// Authentication Routes (using Laravel Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'maintenance'])->group(function () {
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
        Route::get('/', [App\Http\Controllers\TimetableController::class, 'index'])->name('index');
        Route::get('/weekly', [App\Http\Controllers\TimetableController::class, 'weeklyOverview'])->name('weekly');
        Route::get('/create', [App\Http\Controllers\TimetableController::class, 'create'])->name('create')->middleware('role:admin,teacher');
        Route::post('/', [App\Http\Controllers\TimetableController::class, 'store'])->name('store')->middleware('role:admin,teacher');
        Route::get('/{timetable}', [App\Http\Controllers\TimetableController::class, 'show'])->name('show');
        Route::get('/{timetable}/edit', [App\Http\Controllers\TimetableController::class, 'edit'])->name('edit')->middleware('role:admin,teacher');
        Route::put('/{timetable}', [App\Http\Controllers\TimetableController::class, 'update'])->name('update')->middleware('role:admin,teacher');
        Route::delete('/{timetable}', [App\Http\Controllers\TimetableController::class, 'destroy'])->name('destroy')->middleware('role:admin,teacher');
    });
    
    // Exam Management Routes
    Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
        Route::get('/', function () {
            return view('exams.index', ['message' => 'Exam management - to be implemented']);
        })->name('index');
    });
    
    // Results Management Routes
    Route::group(['prefix' => 'results', 'as' => 'results.'], function () {
        Route::get('/', [App\Http\Controllers\ExamResultController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExamResultController::class, 'create'])->name('create')->middleware('role:admin,teacher');
        Route::post('/', [App\Http\Controllers\ExamResultController::class, 'store'])->name('store')->middleware('role:admin,teacher');
        Route::get('/bulk-import', [App\Http\Controllers\ExamResultController::class, 'showBulkImport'])->name('bulk-import')->middleware('role:admin,teacher');
        Route::post('/bulk-import', [App\Http\Controllers\ExamResultController::class, 'bulkImport'])->name('bulk-import.store')->middleware('role:admin,teacher');
        Route::get('/export', [App\Http\Controllers\ExamResultController::class, 'export'])->name('export')->middleware('role:admin,teacher');
        Route::get('/statistics', [App\Http\Controllers\ExamResultController::class, 'statistics'])->name('statistics');
        Route::get('/{result}', [App\Http\Controllers\ExamResultController::class, 'show'])->name('show');
        Route::get('/{result}/edit', [App\Http\Controllers\ExamResultController::class, 'edit'])->name('edit')->middleware('role:admin,teacher');
        Route::put('/{result}', [App\Http\Controllers\ExamResultController::class, 'update'])->name('update')->middleware('role:admin,teacher');
        Route::delete('/{result}', [App\Http\Controllers\ExamResultController::class, 'destroy'])->name('destroy')->middleware('role:admin,teacher');
    });        

    // Announcements Routes
    Route::group(['prefix' => 'announcements', 'as' => 'announcements.'], function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('create')->middleware('role:admin,teacher');
        Route::post('/', [AnnouncementController::class, 'store'])->name('store')->middleware('role:admin,teacher');
        Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('show');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit')->middleware('role:admin,teacher');
        Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update')->middleware('role:admin,teacher');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy')->middleware('role:admin,teacher');
        Route::patch('/{announcement}/toggle-publish', [AnnouncementController::class, 'togglePublish'])->name('toggle-publish')->middleware('role:admin,teacher');
        Route::get('/{announcement}/download', [AnnouncementController::class, 'downloadAttachment'])->name('download');
        Route::get('/dashboard/data', [AnnouncementController::class, 'dashboard'])->name('dashboard');
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
        Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/group/{group}', [App\Http\Controllers\SettingsController::class, 'showGroup'])->name('group')->middleware('role:admin');
        Route::put('/group/{group}', [App\Http\Controllers\SettingsController::class, 'updateGroup'])->name('update-group')->middleware('role:admin');
        Route::get('/create', [App\Http\Controllers\SettingsController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [App\Http\Controllers\SettingsController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{setting}/edit', [App\Http\Controllers\SettingsController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{setting}', [App\Http\Controllers\SettingsController::class, 'updateSetting'])->name('update-setting')->middleware('role:admin');
        Route::delete('/{setting}', [App\Http\Controllers\SettingsController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::post('/clear-cache', [App\Http\Controllers\SettingsController::class, 'clearCache'])->name('clear-cache')->middleware('role:admin');
        Route::post('/reset-defaults', [App\Http\Controllers\SettingsController::class, 'resetToDefaults'])->name('reset-defaults')->middleware('role:admin');
    });

    // Public Carousel Route (for homepage)
    Route::get('/carousel/active', [CarouselController::class, 'getActiveSlides'])->name('carousel.active');

    // Carousel Management Routes (Admin only)
    Route::group(['prefix' => 'admin/carousel', 'as' => 'admin.carousel.'], function () {
        Route::get('/', [CarouselController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', [CarouselController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [CarouselController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{slide}/edit', [CarouselController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{slide}', [CarouselController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{slide}', [CarouselController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::post('/update-order', [CarouselController::class, 'updateOrder'])->name('update-order')->middleware('role:admin');
        Route::patch('/{slide}/toggle-status', [CarouselController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
    });

    // Contact Management Routes (Admin only)
    Route::group(['prefix' => 'admin/contact', 'as' => 'admin.contact.'], function () {
        Route::get('/', [ContactController::class, 'adminIndex'])->name('index')->middleware('role:admin');
        Route::get('/{inquiry}', [ContactController::class, 'show'])->name('show')->middleware('role:admin');
        Route::patch('/{inquiry}/toggle-read', [ContactController::class, 'toggleRead'])->name('toggle-read')->middleware('role:admin');
        Route::delete('/{inquiry}', [ContactController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::get('/stats', [ContactController::class, 'getStats'])->name('stats')->middleware('role:admin');
        Route::get('/export', [ContactController::class, 'export'])->name('export')->middleware('role:admin');
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

// Language test route
Route::get('/test-lang', function () {
    return view('test-lang');
});

// Simple test route
Route::get('/test-simple', function () {
    return 'Simple test route working! Current locale: ' . app()->getLocale();
});



require __DIR__.'/auth.php';
