<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ManagingCommitteeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\GalleryController;
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

// Additional public routes
Route::get('/about', [WebController::class, 'about'])->name('about');
Route::get('/gallery', [WebController::class, 'gallery'])->name('gallery');
Route::get('/programs', [WebController::class, 'programs'])->name('programs');
Route::get('/admissions', [WebController::class, 'admissions'])->name('admissions');
Route::get('/facilities', [WebController::class, 'facilities'])->name('facilities');
Route::get('/news', [WebController::class, 'news'])->name('news');
Route::get('/events', [WebController::class, 'events'])->name('events');
Route::get('/help', [WebController::class, 'help'])->name('help');
// Route moved to avoid conflict with announcements.public.show

// API routes for home page
Route::get('/api/stats', [WebController::class, 'getStats'])->name('api.stats');
Route::get('/api/carousel/active', [WebController::class, 'getCarouselSlides'])->name('api.carousel.active');
Route::get('/api/events/upcoming', [EventController::class, 'upcoming'])->name('api.events.upcoming');


// Authentication Routes (using Laravel Breeze)
Route::get('/dashboard', function () {
    return redirect()->route('school.dashboard');
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
        Route::get('/', [App\Http\Controllers\ClassController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ClassController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ClassController::class, 'store'])->name('store');
        Route::get('/{class}', [App\Http\Controllers\ClassController::class, 'show'])->name('show');
        Route::get('/{class}/edit', [App\Http\Controllers\ClassController::class, 'edit'])->name('edit');
        Route::put('/{class}', [App\Http\Controllers\ClassController::class, 'update'])->name('update');
        Route::delete('/{class}', [App\Http\Controllers\ClassController::class, 'destroy'])->name('destroy');
        Route::post('/{class}/toggle-status', [App\Http\Controllers\ClassController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{class}/assign-subjects', [App\Http\Controllers\ClassController::class, 'assignSubjects'])->name('assign-subjects');
        Route::get('/statistics/data', [App\Http\Controllers\ClassController::class, 'statistics'])->name('statistics');
        Route::get('/{class}/students', [App\Http\Controllers\ClassController::class, 'getStudents'])->name('students');
    });

    // Room Management Routes
    Route::group(['prefix' => 'rooms', 'as' => 'rooms.'], function () {
        Route::get('/', [App\Http\Controllers\RoomController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\RoomController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\RoomController::class, 'store'])->name('store');
        Route::get('/{room}', [App\Http\Controllers\RoomController::class, 'show'])->name('show');
    });
    
    // Subject Management Routes
    Route::group(['prefix' => 'subjects', 'as' => 'subjects.'], function () {
        Route::get('/', [App\Http\Controllers\SubjectController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SubjectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SubjectController::class, 'store'])->name('store');
        Route::get('/{subject}', [App\Http\Controllers\SubjectController::class, 'show'])->name('show');
        Route::get('/{subject}/edit', [App\Http\Controllers\SubjectController::class, 'edit'])->name('edit');
        Route::put('/{subject}', [App\Http\Controllers\SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->name('destroy');
        Route::post('/{subject}/toggle-status', [App\Http\Controllers\SubjectController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{subject}/assign-classes', [App\Http\Controllers\SubjectController::class, 'assignToClasses'])->name('assign-classes');
        Route::get('/statistics/data', [App\Http\Controllers\SubjectController::class, 'statistics'])->name('statistics');
        Route::get('/{subject}/classes', [App\Http\Controllers\SubjectController::class, 'getClasses'])->name('classes');
    });
    
    // Teacher Management Routes
    Route::group(['prefix' => 'teachers', 'as' => 'teachers.'], function () {
        Route::get('/', [App\Http\Controllers\TeacherController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\TeacherManagementController::class, 'dashboard'])->name('dashboard')->middleware('role:admin');
        Route::get('/performance', [App\Http\Controllers\TeacherManagementController::class, 'performance'])->name('performance')->middleware('role:admin');
        Route::get('/create', [App\Http\Controllers\TeacherController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}', [App\Http\Controllers\TeacherController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [App\Http\Controllers\TeacherController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [App\Http\Controllers\TeacherController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [App\Http\Controllers\TeacherController::class, 'destroy'])->name('destroy');
        Route::post('/{teacher}/toggle-status', [App\Http\Controllers\TeacherController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{teacher}/assign-subjects', [App\Http\Controllers\TeacherController::class, 'assignSubjects'])->name('assign-subjects');
        Route::get('/statistics/data', [App\Http\Controllers\TeacherController::class, 'statistics'])->name('statistics');
        Route::get('/profile/view', [App\Http\Controllers\TeacherController::class, 'profile'])->name('profile');
        
        // Teacher Management Routes (Admin only)
        Route::post('/bulk-action', [App\Http\Controllers\TeacherManagementController::class, 'bulkAction'])->name('bulk-action')->middleware('role:admin');
        Route::get('/export/data', [App\Http\Controllers\TeacherManagementController::class, 'export'])->name('export')->middleware('role:admin');
    });

    // Staff Management (Admin)
    Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::get('/', [App\Http\Controllers\StaffPageController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', [App\Http\Controllers\StaffPageController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [App\Http\Controllers\StaffPageController::class, 'store'])->name('store')->middleware('role:admin');
    });

    // Employee Attendance (Admin)
    Route::group(['prefix' => 'employee-attendance', 'as' => 'employee-attendance.'], function () {
        Route::get('/', [App\Http\Controllers\EmployeeAttendancePageController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', function() { return view('employee_attendance.create', ['pageTitle' => __('app.mark_employee_attendance')]); })->name('create')->middleware('role:admin');
    });

    // Leaves (Admin and Self)
    Route::group(['prefix' => 'leaves', 'as' => 'leaves.'], function () {
        Route::get('/', [App\Http\Controllers\LeavePageController::class, 'index'])->name('index');
        Route::get('/my', [App\Http\Controllers\LeavePageController::class, 'my'])->name('my');
        Route::get('/all', [App\Http\Controllers\LeavePageController::class, 'all'])->name('all')->middleware('role:admin');
        Route::get('/apply', function() { return view('leaves.create', ['pageTitle' => __('app.apply_leave')]); })->name('create');
        Route::post('/', [App\Http\Controllers\LeavePageController::class, 'store'])->name('store');
        Route::get('/{leave}', [App\Http\Controllers\LeavePageController::class, 'show'])->name('show');
    });

    // Payroll (Admin and Self)
    Route::group(['prefix' => 'payroll', 'as' => 'payroll.'], function () {
        Route::get('/', [App\Http\Controllers\PayrollPageController::class, 'index'])->name('index');
        Route::get('/create', function() { return view('payroll.create', ['pageTitle' => __('app.generate_payroll')]); })->name('create');
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
        
        // AJAX routes for enhanced UI
        Route::post('/{timetable}/move', [App\Http\Controllers\TimetableController::class, 'move'])->name('move')->middleware('role:admin,teacher');
        Route::post('/{timetable}/room', [App\Http\Controllers\TimetableController::class, 'updateRoom'])->name('room')->middleware('role:admin,teacher');
    });
    
    // Exam Management Routes
    Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
        Route::get('/', [App\Http\Controllers\ExamController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExamController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ExamController::class, 'store'])->name('store');
        Route::get('/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('show');
        Route::get('/{exam}/edit', [App\Http\Controllers\ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [App\Http\Controllers\ExamController::class, 'update'])->name('update');
        Route::delete('/{exam}', [App\Http\Controllers\ExamController::class, 'destroy'])->name('destroy');
        Route::post('/{exam}/toggle-publish', [App\Http\Controllers\ExamController::class, 'togglePublish'])->name('toggle-publish');
        Route::get('/statistics/data', [App\Http\Controllers\ExamController::class, 'statistics'])->name('statistics');
        Route::get('/upcoming/data', [App\Http\Controllers\ExamController::class, 'getUpcomingExams'])->name('upcoming');
        Route::get('/class/{class}/data', [App\Http\Controllers\ExamController::class, 'getByClass'])->name('by-class');
        Route::get('/subject/{subject}/data', [App\Http\Controllers\ExamController::class, 'getBySubject'])->name('by-subject');
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

    // Enrollment Routes
    Route::group(['prefix' => 'enrollments', 'as' => 'enrollments.'], function () {
        Route::get('/dashboard', [EnrollmentController::class, 'dashboard'])->name('dashboard')->middleware('role:admin,teacher');
        Route::get('/reports', [EnrollmentController::class, 'reports'])->name('reports')->middleware('role:admin,teacher');
        Route::get('/statistics', [EnrollmentController::class, 'statistics'])->name('statistics')->middleware('role:admin,teacher');
        Route::post('/bulk-enroll', [EnrollmentController::class, 'bulkEnroll'])->name('bulk-enroll')->middleware('role:admin');
        Route::post('/promote-class', [EnrollmentController::class, 'promoteClass'])->name('promote-class')->middleware('role:admin');
    });

    // Student-specific enrollment routes
    Route::group(['prefix' => 'students/{student}/enrollments', 'as' => 'enrollments.'], function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index')->middleware('role:admin,teacher');
        Route::post('/', [EnrollmentController::class, 'store'])->name('store')->middleware('role:admin,teacher');
    });


    // Teacher Scheduling Routes
    Route::group(['prefix' => 'teacher-scheduling', 'as' => 'teacher-scheduling.'], function () {
        Route::get('/dashboard', [App\Http\Controllers\TeacherSchedulingController::class, 'dashboard'])->name('dashboard')->middleware('role:admin,teacher');
        Route::get('/workload-analytics', [App\Http\Controllers\TeacherSchedulingController::class, 'workloadAnalytics'])->name('workload-analytics')->middleware('role:admin,teacher');
        Route::get('/create', [App\Http\Controllers\TeacherSchedulingController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [App\Http\Controllers\TeacherSchedulingController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{schedule}/edit', [App\Http\Controllers\TeacherSchedulingController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{schedule}', [App\Http\Controllers\TeacherSchedulingController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{schedule}', [App\Http\Controllers\TeacherSchedulingController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::patch('/{schedule}/toggle-status', [App\Http\Controllers\TeacherSchedulingController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
        Route::post('/bulk-action', [App\Http\Controllers\TeacherSchedulingController::class, 'bulkAction'])->name('bulk-action')->middleware('role:admin');
        Route::get('/teachers/{teacher}/schedule', [App\Http\Controllers\TeacherSchedulingController::class, 'showTeacherSchedule'])->name('teacher-schedule')->middleware('role:admin,teacher');
        Route::get('/api/available-slots', [App\Http\Controllers\TeacherSchedulingController::class, 'getAvailableSlots'])->name('available-slots')->middleware('role:admin,teacher');
        Route::get('/api/check-conflicts', [App\Http\Controllers\TeacherSchedulingController::class, 'checkConflicts'])->name('check-conflicts')->middleware('role:admin,teacher');
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

    // Managing Committee Routes
    Route::group(['prefix' => 'managing-committees', 'as' => 'managing-committees.'], function () {
        Route::get('/', [ManagingCommitteeController::class, 'index'])->name('index');
        Route::get('/create', [ManagingCommitteeController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [ManagingCommitteeController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{managingCommittee}', [ManagingCommitteeController::class, 'show'])->name('show');
        Route::get('/{managingCommittee}/edit', [ManagingCommitteeController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{managingCommittee}', [ManagingCommitteeController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{managingCommittee}', [ManagingCommitteeController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::patch('/{managingCommittee}/toggle-status', [ManagingCommitteeController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
        Route::patch('/{managingCommittee}/toggle-featured', [ManagingCommitteeController::class, 'toggleFeatured'])->name('toggle-featured')->middleware('role:admin');
        Route::post('/bulk-action', [ManagingCommitteeController::class, 'bulkAction'])->name('bulk-action')->middleware('role:admin');
    });

    // Events Management Routes (Admin only)
    Route::group(['prefix' => 'admin/events', 'as' => 'events.'], function () {
        Route::get('/', [EventController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', [EventController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [EventController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{event}', [EventController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::patch('/{event}/toggle-publish', [EventController::class, 'togglePublish'])->name('toggle-publish')->middleware('role:admin');
    });
    
    // Parent Management Routes
    Route::group(['prefix' => 'parents', 'as' => 'parents.'], function () {
        Route::get('/', [App\Http\Controllers\ParentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ParentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ParentController::class, 'store'])->name('store');
        Route::get('/{parent}', [App\Http\Controllers\ParentController::class, 'show'])->name('show');
        Route::get('/{parent}/edit', [App\Http\Controllers\ParentController::class, 'edit'])->name('edit');
        Route::put('/{parent}', [App\Http\Controllers\ParentController::class, 'update'])->name('update');
        Route::delete('/{parent}', [App\Http\Controllers\ParentController::class, 'destroy'])->name('destroy');
        Route::post('/{parent}/toggle-status', [App\Http\Controllers\ParentController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{parent}/assign-students', [App\Http\Controllers\ParentController::class, 'assignStudents'])->name('assign-students');
        Route::get('/statistics/data', [App\Http\Controllers\ParentController::class, 'statistics'])->name('statistics');
        Route::get('/{parent}/children', [App\Http\Controllers\ParentController::class, 'getChildren'])->name('children');
    });
    
    // Reports Routes
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/academic-performance', [App\Http\Controllers\ReportController::class, 'academicPerformance'])->name('academic-performance');
        Route::get('/attendance', [App\Http\Controllers\ReportController::class, 'attendance'])->name('attendance');
        Route::get('/student-analytics', [App\Http\Controllers\ReportController::class, 'studentAnalytics'])->name('student-analytics');
        Route::get('/teacher-performance', [App\Http\Controllers\ReportController::class, 'teacherPerformance'])->name('teacher-performance');
        Route::get('/class-performance', [App\Http\Controllers\ReportController::class, 'classPerformance'])->name('class-performance');
        Route::post('/export', [App\Http\Controllers\ReportController::class, 'export'])->name('export');
        Route::get('/dashboard/data', [App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');
    });

    // Admin Gallery Management
    Route::group(['prefix' => 'admin/gallery', 'as' => 'admin.gallery.'], function () {
        Route::get('/', [GalleryController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', [GalleryController::class, 'create'])->name('create')->middleware('role:admin,teacher,staff');
        Route::post('/', [GalleryController::class, 'store'])->name('store')->middleware('role:admin,teacher,staff');
        Route::get('/{galleryImage}/edit', [GalleryController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{galleryImage}', [GalleryController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{galleryImage}', [GalleryController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::patch('/{galleryImage}/toggle-featured', [GalleryController::class, 'toggleFeatured'])->name('toggle-featured')->middleware('role:admin');
    });

    // Gradebook (Admin/Teacher)
    Route::get('/gradebook', [GradebookController::class, 'index'])->name('gradebook.index')->middleware('role:admin,teacher');

    // Student Report Card & Transcript (authorized roles)
    Route::get('/reports/report-cards', [ReportCardController::class, 'index'])->name('reports.report-cards');
    Route::get('/reports/transcripts', [TranscriptController::class, 'index'])->name('reports.transcripts');
    Route::get('/students/{student}/report-card', [ReportCardController::class, 'show'])->name('students.report-card');
    Route::get('/students/{student}/transcript', [TranscriptController::class, 'show'])->name('students.transcript');
    
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

    // Public Carousel Route (for homepage) - moved to API routes to avoid CSRF issues

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

    // Fee Management Routes
    Route::group(['prefix' => 'fees', 'as' => 'fees.'], function () {
        Route::get('/', [App\Http\Controllers\FeeManagementController::class, 'index'])->name('dashboard');
        Route::get('/students', [App\Http\Controllers\FeeManagementController::class, 'studentFees'])->name('students');
        Route::get('/students/{student}/details', [App\Http\Controllers\FeeManagementController::class, 'studentFeeDetails'])->name('student-details');
        Route::get('/collect', [App\Http\Controllers\FeeManagementController::class, 'collectFee'])->name('collect');
        Route::post('/process-payment', [App\Http\Controllers\FeeManagementController::class, 'processPayment'])->name('process-payment');
        Route::get('/reports', [App\Http\Controllers\FeeManagementController::class, 'reports'])->name('reports');
        Route::post('/generate-installments', [App\Http\Controllers\FeeManagementController::class, 'generateInstallments'])->name('generate-installments');
    });

    // Fee Categories Routes (Admin only)
    Route::group(['prefix' => 'fee-categories', 'as' => 'fee-categories.'], function () {
        Route::get('/', [App\Http\Controllers\FeeCategoryController::class, 'index'])->name('index')->middleware('role:admin');
        Route::get('/create', [App\Http\Controllers\FeeCategoryController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [App\Http\Controllers\FeeCategoryController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{feeCategory}', [App\Http\Controllers\FeeCategoryController::class, 'show'])->name('show')->middleware('role:admin');
        Route::get('/{feeCategory}/edit', [App\Http\Controllers\FeeCategoryController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{feeCategory}', [App\Http\Controllers\FeeCategoryController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{feeCategory}', [App\Http\Controllers\FeeCategoryController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::post('/{feeCategory}/toggle-status', [App\Http\Controllers\FeeCategoryController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
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
// Library Management Routes
Route::group(['prefix' => 'library', 'as' => 'library.', 'middleware' => ['auth']], function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\LibraryController::class, 'dashboard'])->name('dashboard');
    
    // Books Management
    Route::resource('books', App\Http\Controllers\LibraryController::class)->except(['show']);
    Route::get('books/{book}', [App\Http\Controllers\LibraryController::class, 'show'])->name('books.show');
    
    // Book Issues Management
    Route::get('/issues', [App\Http\Controllers\LibraryController::class, 'issues'])->name('issues');
    Route::post('/issue-book', [App\Http\Controllers\LibraryController::class, 'issueBook'])->name('issue-book');
    Route::post('/return-book/{issue}', [App\Http\Controllers\LibraryController::class, 'returnBook'])->name('return-book');
    Route::post('/renew-book/{issue}', [App\Http\Controllers\LibraryController::class, 'renewBook'])->name('renew-book');
    
    // Reports
            Route::get('/reports', [App\Http\Controllers\LibraryController::class, 'reports'])->name('reports');
            Route::get('/search', [App\Http\Controllers\LibraryController::class, 'search'])->name('search');
            Route::get('/suggestions', [App\Http\Controllers\LibraryController::class, 'suggestions'])->name('suggestions');
            Route::get('/export', [App\Http\Controllers\LibraryController::class, 'export'])->name('export');
        });

Route::get('/test-simple', function () {
    return 'Simple test route working! Current locale: ' . app()->getLocale();
});

// Settings routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/{group}', [App\Http\Controllers\SettingsController::class, 'showGroup'])->name('settings.group');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/timing', [App\Http\Controllers\SettingsController::class, 'updateTiming'])->name('settings.timing.update');
    Route::post('/settings/format', [App\Http\Controllers\SettingsController::class, 'updateFormat'])->name('settings.format.update');
    Route::post('/settings/{group}', [App\Http\Controllers\SettingsController::class, 'updateGroup'])->name('settings.group.update');
});

require __DIR__.'/auth.php';
