<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class BreadcrumbHelper
{
    /**
     * Generate breadcrumbs based on current route
     */
    public static function generate()
    {
        $routeName = Route::currentRouteName();
        $breadcrumbs = [];

        // Always start with home
        $breadcrumbs[] = [
            'label' => __('app.dashboard'),
            'url' => route('school.dashboard')
        ];

        // Generate breadcrumbs based on route name
        switch ($routeName) {
            // Student Management
            case 'students.index':
                $breadcrumbs[] = ['label' => __('app.students')];
                break;
            case 'students.create':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.add_student')];
                break;
            case 'students.edit':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.edit_student')];
                break;
            case 'students.show':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.student_details')];
                break;

            // Parents Management
            case 'parents.index':
                $breadcrumbs[] = ['label' => __('app.parents')];
                break;
            case 'parents.create':
                $breadcrumbs[] = ['label' => __('app.parents'), 'url' => route('parents.index')];
                $breadcrumbs[] = ['label' => __('app.add_parent')];
                break;
            case 'parents.edit':
                $breadcrumbs[] = ['label' => __('app.parents'), 'url' => route('parents.index')];
                $breadcrumbs[] = ['label' => __('app.edit_parent')];
                break;

            // Attendance
            case 'attendance.index':
                $breadcrumbs[] = ['label' => __('app.attendance')];
                break;
            case 'attendance.class-report':
                $breadcrumbs[] = ['label' => __('app.attendance'), 'url' => route('attendance.index')];
                $breadcrumbs[] = ['label' => __('app.class_report')];
                break;
            case 'attendance.student-report':
                $breadcrumbs[] = ['label' => __('app.attendance'), 'url' => route('attendance.index')];
                $breadcrumbs[] = ['label' => __('app.student_report')];
                break;
            case 'attendance.statistics':
                $breadcrumbs[] = ['label' => __('app.attendance'), 'url' => route('attendance.index')];
                $breadcrumbs[] = ['label' => __('app.statistics')];
                break;

            // Teacher Management
            case 'teachers.index':
                $breadcrumbs[] = ['label' => __('app.teachers')];
                break;
            case 'teachers.create':
                $breadcrumbs[] = ['label' => __('app.teachers'), 'url' => route('teachers.index')];
                $breadcrumbs[] = ['label' => __('app.add_teacher')];
                break;
            case 'teachers.edit':
                $breadcrumbs[] = ['label' => __('app.teachers'), 'url' => route('teachers.index')];
                $breadcrumbs[] = ['label' => __('app.edit_teacher')];
                break;
            case 'teachers.show':
                $breadcrumbs[] = ['label' => __('app.teachers'), 'url' => route('teachers.index')];
                $breadcrumbs[] = ['label' => __('app.teacher_details')];
                break;

            // Classes Management
            case 'classes.index':
                $breadcrumbs[] = ['label' => __('app.classes')];
                break;
            case 'classes.create':
                $breadcrumbs[] = ['label' => __('app.classes'), 'url' => route('classes.index')];
                $breadcrumbs[] = ['label' => __('app.add_class')];
                break;
            case 'classes.edit':
                $breadcrumbs[] = ['label' => __('app.classes'), 'url' => route('classes.index')];
                $breadcrumbs[] = ['label' => __('app.edit_class')];
                break;

            // Subjects Management
            case 'subjects.index':
                $breadcrumbs[] = ['label' => __('app.subjects')];
                break;
            case 'subjects.create':
                $breadcrumbs[] = ['label' => __('app.subjects'), 'url' => route('subjects.index')];
                $breadcrumbs[] = ['label' => __('app.add_subject')];
                break;
            case 'subjects.edit':
                $breadcrumbs[] = ['label' => __('app.subjects'), 'url' => route('subjects.index')];
                $breadcrumbs[] = ['label' => __('app.edit_subject')];
                break;

            // Exams Management
            case 'exams.index':
                $breadcrumbs[] = ['label' => __('app.exams')];
                break;
            case 'exams.create':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.add_exam')];
                break;
            case 'exams.edit':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.edit_exam')];
                break;
            case 'exams.show':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.exam_details')];
                break;

            // Results Management
            case 'results.index':
                $breadcrumbs[] = ['label' => __('app.results')];
                break;
            case 'results.create':
                $breadcrumbs[] = ['label' => __('app.results'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.add_result')];
                break;
            case 'results.edit':
                $breadcrumbs[] = ['label' => __('app.results'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.edit_result')];
                break;

            // Announcements
            case 'announcements.index':
                $breadcrumbs[] = ['label' => __('app.announcements')];
                break;
            case 'announcements.create':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.add_announcement')];
                break;
            case 'announcements.edit':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.edit_announcement')];
                break;
            case 'announcements.show':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.announcement_details')];
                break;

            // Yearly Leave Settings
            case 'yearly-leaves.index':
                $breadcrumbs[] = ['label' => __('app.yearly_leave_settings')];
                break;
            case 'yearly-leaves.create':
                $breadcrumbs[] = ['label' => __('app.yearly_leave_settings'), 'url' => route('yearly-leaves.index')];
                $breadcrumbs[] = ['label' => __('app.add_yearly_leave')];
                break;
            case 'yearly-leaves.edit':
                $breadcrumbs[] = ['label' => __('app.yearly_leave_settings'), 'url' => route('yearly-leaves.index')];
                $breadcrumbs[] = ['label' => __('app.edit_yearly_leave')];
                break;
            case 'yearly-leaves.show':
                $breadcrumbs[] = ['label' => __('app.yearly_leave_settings'), 'url' => route('yearly-leaves.index')];
                $breadcrumbs[] = ['label' => __('app.yearly_leave_details')];
                break;

            // Events
            case 'events.index':
                $breadcrumbs[] = ['label' => __('app.events')];
                break;
            case 'events.create':
                $breadcrumbs[] = ['label' => __('app.events'), 'url' => route('events.index')];
                $breadcrumbs[] = ['label' => __('app.add_event')];
                break;
            case 'events.edit':
                $breadcrumbs[] = ['label' => __('app.events'), 'url' => route('events.index')];
                $breadcrumbs[] = ['label' => __('app.edit_event')];
                break;

            // Gallery
            case 'admin.gallery.index':
                $breadcrumbs[] = ['label' => __('app.gallery')];
                break;

            // Settings
            case 'settings.index':
                $breadcrumbs[] = ['label' => __('app.settings')];
                break;
            case 'settings.group':
                $breadcrumbs[] = ['label' => __('app.settings'), 'url' => route('settings.index')];
                $breadcrumbs[] = ['label' => ucfirst(request('group', 'settings'))];
                break;

            // Reports
            case 'reports.index':
                $breadcrumbs[] = ['label' => __('app.reports')];
                break;

            // Fee Management
            case 'fees.dashboard':
                $breadcrumbs[] = ['label' => __('app.fee_management')];
                break;
            case 'fee-categories.index':
                $breadcrumbs[] = ['label' => __('app.fee_categories')];
                break;
            case 'fee-categories.create':
                $breadcrumbs[] = ['label' => __('app.fee_categories'), 'url' => route('fee-categories.index')];
                $breadcrumbs[] = ['label' => __('app.add_fee_category')];
                break;
            case 'fee-categories.edit':
                $breadcrumbs[] = ['label' => __('app.fee_categories'), 'url' => route('fee-categories.index')];
                $breadcrumbs[] = ['label' => __('app.edit_fee_category')];
                break;

            // Library Management
            case 'library.dashboard':
                $breadcrumbs[] = ['label' => __('app.library')];
                break;
            case 'library.books.index':
                $breadcrumbs[] = ['label' => __('app.library'), 'url' => route('library.dashboard')];
                $breadcrumbs[] = ['label' => __('app.books')];
                break;
            case 'library.books.create':
                $breadcrumbs[] = ['label' => __('app.library'), 'url' => route('library.dashboard')];
                $breadcrumbs[] = ['label' => __('app.books'), 'url' => route('library.books.index')];
                $breadcrumbs[] = ['label' => __('app.add_book')];
                break;
            case 'library.books.edit':
                $breadcrumbs[] = ['label' => __('app.library'), 'url' => route('library.dashboard')];
                $breadcrumbs[] = ['label' => __('app.books'), 'url' => route('library.books.index')];
                $breadcrumbs[] = ['label' => __('app.edit_book')];
                break;
            case 'library.books.show':
                $breadcrumbs[] = ['label' => __('app.library'), 'url' => route('library.dashboard')];
                $breadcrumbs[] = ['label' => __('app.books'), 'url' => route('library.books.index')];
                $breadcrumbs[] = ['label' => __('app.book_details')];
                break;

            // Contact Management
            case 'admin.contact.index':
                $breadcrumbs[] = ['label' => __('app.contact_management')];
                break;

            // Managing Committees
            case 'managing-committees.index':
                $breadcrumbs[] = ['label' => __('app.managing_committees')];
                break;
            case 'managing-committees.create':
                $breadcrumbs[] = ['label' => __('app.managing_committees'), 'url' => route('managing-committees.index')];
                $breadcrumbs[] = ['label' => __('app.add_managing_committee')];
                break;
            case 'managing-committees.edit':
                $breadcrumbs[] = ['label' => __('app.managing_committees'), 'url' => route('managing-committees.index')];
                $breadcrumbs[] = ['label' => __('app.edit_managing_committee')];
                break;

            // Leave Management
            case 'leaves.index':
                $breadcrumbs[] = ['label' => __('app.leaves')];
                break;
            case 'leaves.my':
                $breadcrumbs[] = ['label' => __('app.leaves'), 'url' => route('leaves.index')];
                $breadcrumbs[] = ['label' => __('app.my_leaves')];
                break;
            case 'leaves.all':
                $breadcrumbs[] = ['label' => __('app.leaves'), 'url' => route('leaves.index')];
                $breadcrumbs[] = ['label' => __('app.all_leaves')];
                break;
            case 'leaves.create':
                $breadcrumbs[] = ['label' => __('app.leaves'), 'url' => route('leaves.index')];
                $breadcrumbs[] = ['label' => __('app.apply_leave')];
                break;
            case 'leaves.show':
                $breadcrumbs[] = ['label' => __('app.leaves'), 'url' => route('leaves.index')];
                $breadcrumbs[] = ['label' => __('app.leave_details')];
                break;

            // Fee Management
            case 'fees.dashboard':
                $breadcrumbs[] = ['label' => __('app.fee_management')];
                break;
            case 'fees.students':
                $breadcrumbs[] = ['label' => __('app.fee_management'), 'url' => route('fees.dashboard')];
                $breadcrumbs[] = ['label' => __('app.student_fees')];
                break;
            case 'fees.student-details':
                $breadcrumbs[] = ['label' => __('app.fee_management'), 'url' => route('fees.dashboard')];
                $breadcrumbs[] = ['label' => __('app.student_fees'), 'url' => route('fees.students')];
                $breadcrumbs[] = ['label' => __('app.student_fee_details')];
                break;
            case 'fees.collect':
                $breadcrumbs[] = ['label' => __('app.fee_management'), 'url' => route('fees.dashboard')];
                $breadcrumbs[] = ['label' => __('app.collect_fee_payment')];
                break;
            case 'fees.reports':
                $breadcrumbs[] = ['label' => __('app.fee_management'), 'url' => route('fees.dashboard')];
                $breadcrumbs[] = ['label' => __('app.fee_reports')];
                break;

            // Admin Gallery Management
            case 'admin.gallery.index':
                $breadcrumbs[] = ['label' => __('app.gallery')];
                break;
            case 'admin.gallery.create':
                $breadcrumbs[] = ['label' => __('app.gallery'), 'url' => route('admin.gallery.index')];
                $breadcrumbs[] = ['label' => __('app.add_image')];
                break;
            case 'admin.gallery.edit':
                $breadcrumbs[] = ['label' => __('app.gallery'), 'url' => route('admin.gallery.index')];
                $breadcrumbs[] = ['label' => __('app.edit_image')];
                break;

            // Teacher Performance
            case 'teachers.performance':
                $breadcrumbs[] = ['label' => __('app.teachers'), 'url' => route('teachers.index')];
                $breadcrumbs[] = ['label' => __('app.performance_analytics')];
                break;

            // Admin Events Management
            case 'events.index':
                $breadcrumbs[] = ['label' => __('app.events')];
                break;
            case 'events.create':
                $breadcrumbs[] = ['label' => __('app.events'), 'url' => route('events.index')];
                $breadcrumbs[] = ['label' => __('app.create_event')];
                break;
            case 'events.edit':
                $breadcrumbs[] = ['label' => __('app.events'), 'url' => route('events.index')];
                $breadcrumbs[] = ['label' => __('app.edit_event')];
                break;

            // Admin Carousel Management
            case 'admin.carousel.index':
                $breadcrumbs[] = ['label' => __('app.carousel_management')];
                break;
            case 'admin.carousel.create':
                $breadcrumbs[] = ['label' => __('app.carousel_management'), 'url' => route('admin.carousel.index')];
                $breadcrumbs[] = ['label' => __('app.add_slide')];
                break;
            case 'admin.carousel.edit':
                $breadcrumbs[] = ['label' => __('app.carousel_management'), 'url' => route('admin.carousel.index')];
                $breadcrumbs[] = ['label' => __('app.edit_slide')];
                break;

            // Admin Contact Management
            case 'admin.contact.index':
                $breadcrumbs[] = ['label' => __('app.contact_management')];
                break;
            case 'admin.contact.show':
                $breadcrumbs[] = ['label' => __('app.contact_management'), 'url' => route('admin.contact.index')];
                $breadcrumbs[] = ['label' => __('app.contact_inquiry_details')];
                break;

            // Managing Committees Management
            case 'managing-committees.index':
                $breadcrumbs[] = ['label' => __('app.managing_committees')];
                break;
            case 'managing-committees.create':
                $breadcrumbs[] = ['label' => __('app.managing_committees'), 'url' => route('managing-committees.index')];
                $breadcrumbs[] = ['label' => __('app.add_committee_member')];
                break;
            case 'managing-committees.show':
                $breadcrumbs[] = ['label' => __('app.managing_committees'), 'url' => route('managing-committees.index')];
                $breadcrumbs[] = ['label' => __('app.committee_member_details')];
                break;
            case 'managing-committees.edit':
                $breadcrumbs[] = ['label' => __('app.managing_committees'), 'url' => route('managing-committees.index')];
                $breadcrumbs[] = ['label' => __('app.edit_committee_member')];
                break;

            // Payroll Management
            case 'payroll.index':
                $breadcrumbs[] = ['label' => __('app.payroll')];
                break;
            case 'payroll.create':
                $breadcrumbs[] = ['label' => __('app.payroll'), 'url' => route('payroll.index')];
                $breadcrumbs[] = ['label' => __('app.generate_payroll')];
                break;

            // Employee Attendance Management
            case 'employee-attendance.index':
                $breadcrumbs[] = ['label' => __('app.employee_attendance')];
                break;
            case 'employee-attendance.create':
                $breadcrumbs[] = ['label' => __('app.employee_attendance'), 'url' => route('employee-attendance.index')];
                $breadcrumbs[] = ['label' => __('app.mark_employee_attendance')];
                break;

            // Staff Management
            case 'staff.index':
                $breadcrumbs[] = ['label' => __('app.staff')];
                break;
            case 'staff.create':
                $breadcrumbs[] = ['label' => __('app.staff'), 'url' => route('staff.index')];
                $breadcrumbs[] = ['label' => __('app.add_staff')];
                break;

            // Reports Management
            case 'reports.index':
                $breadcrumbs[] = ['label' => __('app.reports_analytics')];
                break;
            case 'reports.academic-performance':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.academic_performance')];
                break;
            case 'reports.attendance':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.attendance_reports')];
                break;
            case 'reports.student-analytics':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.student_analytics')];
                break;
            case 'reports.teacher-performance':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.teacher_performance')];
                break;
            case 'reports.class-performance':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.class_performance')];
                break;
            case 'reports.transcripts':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.transcripts')];
                break;
            case 'students.transcript':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.transcripts'), 'url' => route('reports.transcripts')];
                $breadcrumbs[] = ['label' => __('app.student_transcript')];
                break;
            case 'reports.report-cards':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.report_cards')];
                break;
            case 'students.report-card':
                $breadcrumbs[] = ['label' => __('app.reports_analytics'), 'url' => route('reports.index')];
                $breadcrumbs[] = ['label' => __('app.report_cards'), 'url' => route('reports.report-cards')];
                $breadcrumbs[] = ['label' => __('app.student_report_card')];
                break;

            // Announcements Management
            case 'announcements.index':
                $breadcrumbs[] = ['label' => __('app.announcements')];
                break;
            case 'announcements.create':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.new_announcement')];
                break;
            case 'announcements.show':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.announcement_details')];
                break;
            case 'announcements.edit':
                $breadcrumbs[] = ['label' => __('app.announcements'), 'url' => route('announcements.index')];
                $breadcrumbs[] = ['label' => __('app.edit_announcement')];
                break;

            // Results Management
            case 'results.index':
                $breadcrumbs[] = ['label' => __('app.results_management')];
                break;
            case 'results.create':
                $breadcrumbs[] = ['label' => __('app.results_management'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.add_result')];
                break;
            case 'results.show':
                $breadcrumbs[] = ['label' => __('app.results_management'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.result_details')];
                break;
            case 'results.edit':
                $breadcrumbs[] = ['label' => __('app.results_management'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.edit_result')];
                break;
            case 'results.bulk-import':
                $breadcrumbs[] = ['label' => __('app.results_management'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.bulk_import')];
                break;
            case 'results.statistics':
                $breadcrumbs[] = ['label' => __('app.results_management'), 'url' => route('results.index')];
                $breadcrumbs[] = ['label' => __('app.statistics')];
                break;

            // Students Management
            case 'students.index':
                $breadcrumbs[] = ['label' => __('app.students')];
                break;
            case 'students.create':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.add_student')];
                break;
            case 'students.show':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.student_details')];
                break;
            case 'students.edit':
                $breadcrumbs[] = ['label' => __('app.students'), 'url' => route('students.index')];
                $breadcrumbs[] = ['label' => __('app.edit_student')];
                break;

            // Classes Management
            case 'classes.index':
                $breadcrumbs[] = ['label' => __('app.classes')];
                break;
            case 'classes.create':
                $breadcrumbs[] = ['label' => __('app.classes'), 'url' => route('classes.index')];
                $breadcrumbs[] = ['label' => __('app.add_class')];
                break;
            case 'classes.show':
                $breadcrumbs[] = ['label' => __('app.classes'), 'url' => route('classes.index')];
                $breadcrumbs[] = ['label' => __('app.class_details')];
                break;
            case 'classes.edit':
                $breadcrumbs[] = ['label' => __('app.classes'), 'url' => route('classes.index')];
                $breadcrumbs[] = ['label' => __('app.edit_class')];
                break;

            // Subjects Management
            case 'subjects.index':
                $breadcrumbs[] = ['label' => __('app.subjects')];
                break;
            case 'subjects.create':
                $breadcrumbs[] = ['label' => __('app.subjects'), 'url' => route('subjects.index')];
                $breadcrumbs[] = ['label' => __('app.add_subject')];
                break;
            case 'subjects.show':
                $breadcrumbs[] = ['label' => __('app.subjects'), 'url' => route('subjects.index')];
                $breadcrumbs[] = ['label' => __('app.subject_details')];
                break;
            case 'subjects.edit':
                $breadcrumbs[] = ['label' => __('app.subjects'), 'url' => route('subjects.index')];
                $breadcrumbs[] = ['label' => __('app.edit_subject')];
                break;

            // Rooms Management
            case 'rooms.index':
                $breadcrumbs[] = ['label' => __('app.rooms')];
                break;
            case 'rooms.create':
                $breadcrumbs[] = ['label' => __('app.rooms'), 'url' => route('rooms.index')];
                $breadcrumbs[] = ['label' => __('app.add_room')];
                break;
            case 'rooms.show':
                $breadcrumbs[] = ['label' => __('app.rooms'), 'url' => route('rooms.index')];
                $breadcrumbs[] = ['label' => __('app.room_details')];
                break;
            case 'rooms.edit':
                $breadcrumbs[] = ['label' => __('app.rooms'), 'url' => route('rooms.index')];
                $breadcrumbs[] = ['label' => __('app.edit_room')];
                break;

            // Exams Management
            case 'exams.index':
                $breadcrumbs[] = ['label' => __('app.exams')];
                break;
            case 'exams.create':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.add_exam')];
                break;
            case 'exams.show':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.exam_details')];
                break;
            case 'exams.edit':
                $breadcrumbs[] = ['label' => __('app.exams'), 'url' => route('exams.index')];
                $breadcrumbs[] = ['label' => __('app.edit_exam')];
                break;

            // Timetable Management
            case 'timetable.index':
                $breadcrumbs[] = ['label' => __('app.timetable')];
                break;
            case 'timetable.create':
                $breadcrumbs[] = ['label' => __('app.timetable'), 'url' => route('timetable.index')];
                $breadcrumbs[] = ['label' => __('app.add_timetable')];
                break;
            case 'timetable.show':
                $breadcrumbs[] = ['label' => __('app.timetable'), 'url' => route('timetable.index')];
                $breadcrumbs[] = ['label' => __('app.timetable_details')];
                break;
            case 'timetable.edit':
                $breadcrumbs[] = ['label' => __('app.timetable'), 'url' => route('timetable.index')];
                $breadcrumbs[] = ['label' => __('app.edit_timetable')];
                break;

            // Attendance Management
            case 'attendance.index':
                $breadcrumbs[] = ['label' => __('app.attendance')];
                break;
            case 'attendance.create':
                $breadcrumbs[] = ['label' => __('app.attendance'), 'url' => route('attendance.index')];
                $breadcrumbs[] = ['label' => __('app.mark_attendance')];
                break;
            case 'attendance.show':
                $breadcrumbs[] = ['label' => __('app.attendance'), 'url' => route('attendance.index')];
                $breadcrumbs[] = ['label' => __('app.attendance_details')];
                break;

            // Parents Management
            case 'parents.index':
                $breadcrumbs[] = ['label' => __('app.parents')];
                break;
            case 'parents.create':
                $breadcrumbs[] = ['label' => __('app.parents'), 'url' => route('parents.index')];
                $breadcrumbs[] = ['label' => __('app.add_parent')];
                break;
            case 'parents.show':
                $breadcrumbs[] = ['label' => __('app.parents'), 'url' => route('parents.index')];
                $breadcrumbs[] = ['label' => __('app.parent_details')];
                break;
            case 'parents.edit':
                $breadcrumbs[] = ['label' => __('app.parents'), 'url' => route('parents.index')];
                $breadcrumbs[] = ['label' => __('app.edit_parent')];
                break;

            // Teachers Dashboard
            case 'teachers.dashboard':
                $breadcrumbs[] = ['label' => __('app.teachers_dashboard')];
                break;

            // Teacher Scheduling
            case 'teacher-scheduling.dashboard':
                $breadcrumbs[] = ['label' => __('app.teacher_scheduling')];
                break;

            // Gradebook
            case 'gradebook.index':
                $breadcrumbs[] = ['label' => __('app.gradebook')];
                break;

            // Default case - just show current page title
            default:
                $breadcrumbs[] = ['label' => ucfirst(str_replace(['.', '-'], ' ', $routeName))];
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Get page title based on current route
     */
    public static function getPageTitle()
    {
        $routeName = Route::currentRouteName();
        
        switch ($routeName) {
            case 'students.index':
                return __('app.students');
            case 'students.create':
                return __('app.add_student');
            case 'students.edit':
                return __('app.edit_student');
            case 'students.show':
                return __('app.student_details');
            case 'parents.index':
                return __('app.parents');
            case 'parents.create':
                return __('app.add_parent');
            case 'parents.edit':
                return __('app.edit_parent');
            case 'attendance.index':
                return __('app.attendance');
            case 'attendance.class-report':
                return __('app.class_report');
            case 'attendance.student-report':
                return __('app.student_report');
            case 'attendance.statistics':
                return __('app.statistics');
            case 'teachers.index':
                return __('app.teachers');
            case 'teachers.create':
                return __('app.add_teacher');
            case 'teachers.edit':
                return __('app.edit_teacher');
            case 'teachers.show':
                return __('app.teacher_details');
            case 'classes.index':
                return __('app.classes');
            case 'classes.create':
                return __('app.add_class');
            case 'classes.edit':
                return __('app.edit_class');
            case 'subjects.index':
                return __('app.subjects');
            case 'subjects.create':
                return __('app.add_subject');
            case 'subjects.edit':
                return __('app.edit_subject');
            case 'exams.index':
                return __('app.exams');
            case 'exams.create':
                return __('app.add_exam');
            case 'exams.edit':
                return __('app.edit_exam');
            case 'exams.show':
                return __('app.exam_details');
            case 'results.index':
                return __('app.results');
            case 'results.create':
                return __('app.add_result');
            case 'results.edit':
                return __('app.edit_result');
            case 'announcements.index':
                return __('app.announcements');
            case 'announcements.create':
                return __('app.add_announcement');
            case 'announcements.edit':
                return __('app.edit_announcement');
            case 'announcements.show':
                return __('app.announcement_details');
            case 'yearly-leaves.index':
                return __('app.yearly_leave_settings');
            case 'yearly-leaves.create':
                return __('app.add_yearly_leave');
            case 'yearly-leaves.edit':
                return __('app.edit_yearly_leave');
            case 'yearly-leaves.show':
                return __('app.yearly_leave_details');
            case 'events.index':
                return __('app.events');
            case 'events.create':
                return __('app.add_event');
            case 'events.edit':
                return __('app.edit_event');
            case 'admin.gallery.index':
                return __('app.gallery');
            case 'settings.index':
                return __('app.settings');
            case 'settings.group':
                return ucfirst(request('group', 'settings'));
            case 'reports.index':
                return __('app.reports');
            case 'fees.dashboard':
                return __('app.fee_management');
            case 'fee-categories.index':
                return __('app.fee_categories');
            case 'fee-categories.create':
                return __('app.add_fee_category');
            case 'fee-categories.edit':
                return __('app.edit_fee_category');
            case 'library.dashboard':
                return __('app.library');
            case 'library.books.index':
                return __('app.books');
            case 'library.books.create':
                return __('app.add_book');
            case 'library.books.edit':
                return __('app.edit_book');
            case 'library.books.show':
                return __('app.book_details');
            case 'admin.contact.index':
                return __('app.contact_management');
            case 'managing-committees.index':
                return __('app.managing_committees');
            case 'managing-committees.create':
                return __('app.add_managing_committee');
            case 'managing-committees.edit':
                return __('app.edit_managing_committee');
            case 'leaves.index':
                return __('app.leaves');
            case 'leaves.my':
                return __('app.my_leaves');
            case 'leaves.all':
                return __('app.all_leaves');
            case 'leaves.create':
                return __('app.apply_leave');
            case 'leaves.show':
                return __('app.leave_details');
            case 'fees.dashboard':
                return __('app.fee_management');
            case 'fees.students':
                return __('app.student_fees');
            case 'fees.student-details':
                return __('app.student_fee_details');
            case 'fees.collect':
                return __('app.collect_fee_payment');
            case 'fees.reports':
                return __('app.fee_reports');
            case 'admin.gallery.index':
                return __('app.gallery');
            case 'admin.gallery.create':
                return __('app.add_image');
            case 'admin.gallery.edit':
                return __('app.edit_image');
            case 'teachers.performance':
                return __('app.performance_analytics');
            case 'events.index':
                return __('app.events');
            case 'events.create':
                return __('app.create_event');
            case 'events.edit':
                return __('app.edit_event');
            case 'admin.carousel.index':
                return __('app.carousel_management');
            case 'admin.carousel.create':
                return __('app.add_slide');
            case 'admin.carousel.edit':
                return __('app.edit_slide');
            case 'admin.contact.index':
                return __('app.contact_management');
            case 'admin.contact.show':
                return __('app.contact_inquiry_details');
            case 'managing-committees.index':
                return __('app.managing_committees');
            case 'managing-committees.create':
                return __('app.add_committee_member');
            case 'managing-committees.show':
                return __('app.committee_member_details');
            case 'managing-committees.edit':
                return __('app.edit_committee_member');
            case 'payroll.index':
                return __('app.payroll');
            case 'payroll.create':
                return __('app.generate_payroll');
            case 'employee-attendance.index':
                return __('app.employee_attendance');
            case 'employee-attendance.create':
                return __('app.mark_employee_attendance');
            case 'staff.index':
                return __('app.staff');
            case 'staff.create':
                return __('app.add_staff');
            case 'reports.index':
                return __('app.reports_analytics');
            case 'reports.academic-performance':
                return __('app.academic_performance');
            case 'reports.attendance':
                return __('app.attendance_reports');
            case 'reports.student-analytics':
                return __('app.student_analytics');
            case 'reports.teacher-performance':
                return __('app.teacher_performance');
            case 'reports.class-performance':
                return __('app.class_performance');
            case 'reports.transcripts':
                return __('app.transcripts');
            case 'students.transcript':
                return __('app.student_transcript');
            case 'reports.report-cards':
                return __('app.report_cards');
            case 'students.report-card':
                return __('app.student_report_card');
            case 'announcements.index':
                return __('app.announcements');
            case 'announcements.create':
                return __('app.new_announcement');
            case 'announcements.show':
                return __('app.announcement_details');
            case 'announcements.edit':
                return __('app.edit_announcement');
            case 'results.index':
                return __('app.results_management');
            case 'results.create':
                return __('app.add_result');
            case 'results.show':
                return __('app.result_details');
            case 'results.edit':
                return __('app.edit_result');
            case 'results.bulk-import':
                return __('app.bulk_import');
            case 'results.statistics':
                return __('app.statistics');
            case 'students.index':
                return __('app.students');
            case 'students.create':
                return __('app.add_student');
            case 'students.show':
                return __('app.student_details');
            case 'students.edit':
                return __('app.edit_student');
            case 'classes.index':
                return __('app.classes');
            case 'classes.create':
                return __('app.add_class');
            case 'classes.show':
                return __('app.class_details');
            case 'classes.edit':
                return __('app.edit_class');
            case 'subjects.index':
                return __('app.subjects');
            case 'subjects.create':
                return __('app.add_subject');
            case 'subjects.show':
                return __('app.subject_details');
            case 'subjects.edit':
                return __('app.edit_subject');
            case 'rooms.index':
                return __('app.rooms');
            case 'rooms.create':
                return __('app.add_room');
            case 'rooms.show':
                return __('app.room_details');
            case 'rooms.edit':
                return __('app.edit_room');
            case 'exams.index':
                return __('app.exams');
            case 'exams.create':
                return __('app.add_exam');
            case 'exams.show':
                return __('app.exam_details');
            case 'exams.edit':
                return __('app.edit_exam');
            case 'timetable.index':
                return __('app.timetable');
            case 'timetable.create':
                return __('app.add_timetable');
            case 'timetable.show':
                return __('app.timetable_details');
            case 'timetable.edit':
                return __('app.edit_timetable');
            case 'attendance.index':
                return __('app.attendance');
            case 'attendance.create':
                return __('app.mark_attendance');
            case 'attendance.show':
                return __('app.attendance_details');
            case 'parents.index':
                return __('app.parents');
            case 'parents.create':
                return __('app.add_parent');
            case 'parents.show':
                return __('app.parent_details');
            case 'parents.edit':
                return __('app.edit_parent');
            case 'teachers.dashboard':
                return __('app.teachers_dashboard');
            case 'teacher-scheduling.dashboard':
                return __('app.teacher_scheduling');
            case 'gradebook.index':
                return __('app.gradebook');
            default:
                return ucfirst(str_replace(['.', '-'], ' ', $routeName));
        }
    }

    /**
     * Get page description based on current route
     */
    public static function getPageDescription()
    {
        $routeName = Route::currentRouteName();
        
        switch ($routeName) {
            case 'students.index':
                return __('app.manage_students_description');
            case 'students.create':
                return __('app.add_new_student_description');
            case 'students.edit':
                return __('app.edit_student_description');
            case 'students.show':
                return __('app.view_student_details_description');
            case 'parents.index':
                return __('app.manage_parents_description');
            case 'parents.create':
                return __('app.add_new_parent_description');
            case 'parents.edit':
                return __('app.edit_parent_description');
            case 'attendance.index':
                return __('app.manage_attendance_description');
            case 'attendance.class-report':
                return __('app.view_class_attendance_report_description');
            case 'attendance.student-report':
                return __('app.view_student_attendance_report_description');
            case 'attendance.statistics':
                return __('app.view_attendance_statistics_description');
            case 'teachers.index':
                return __('app.manage_teachers_description');
            case 'teachers.create':
                return __('app.add_new_teacher_description');
            case 'teachers.edit':
                return __('app.edit_teacher_description');
            case 'teachers.show':
                return __('app.view_teacher_details_description');
            case 'classes.index':
                return __('app.manage_classes_description');
            case 'classes.create':
                return __('app.add_new_class_description');
            case 'classes.edit':
                return __('app.edit_class_description');
            case 'subjects.index':
                return __('app.manage_subjects_description');
            case 'subjects.create':
                return __('app.add_new_subject_description');
            case 'subjects.edit':
                return __('app.edit_subject_description');
            case 'exams.index':
                return __('app.manage_exams_description');
            case 'exams.create':
                return __('app.add_new_exam_description');
            case 'exams.edit':
                return __('app.edit_exam_description');
            case 'exams.show':
                return __('app.view_exam_details_description');
            case 'results.index':
                return __('app.manage_results_description');
            case 'results.create':
                return __('app.add_new_result_description');
            case 'results.edit':
                return __('app.edit_result_description');
            case 'announcements.index':
                return __('app.manage_announcements_description');
            case 'announcements.create':
                return __('app.add_new_announcement_description');
            case 'announcements.edit':
                return __('app.edit_announcement_description');
            case 'announcements.show':
                return __('app.view_announcement_details_description');
            case 'yearly-leaves.index':
                return __('app.manage_yearly_leave_settings_description');
            case 'yearly-leaves.create':
                return __('app.add_new_yearly_leave_description');
            case 'yearly-leaves.edit':
                return __('app.edit_yearly_leave_description');
            case 'yearly-leaves.show':
                return __('app.view_yearly_leave_details_description');
            case 'events.index':
                return __('app.manage_events_description');
            case 'events.create':
                return __('app.add_new_event_description');
            case 'events.edit':
                return __('app.edit_event_description');
            case 'admin.gallery.index':
                return __('app.manage_gallery_description');
            case 'settings.index':
                return __('app.manage_system_settings_description');
            case 'settings.group':
                return __('app.manage_group_settings_description', ['group' => ucfirst(request('group', 'settings'))]);
            case 'reports.index':
                return __('app.view_reports_description');
            case 'fees.dashboard':
                return __('app.manage_fee_management_description');
            case 'fee-categories.index':
                return __('app.manage_fee_categories_description');
            case 'fee-categories.create':
                return __('app.add_new_fee_category_description');
            case 'fee-categories.edit':
                return __('app.edit_fee_category_description');
            case 'library.dashboard':
                return __('app.manage_library_description');
            case 'library.books.index':
                return __('app.manage_books_description');
            case 'library.books.create':
                return __('app.add_new_book_description');
            case 'library.books.edit':
                return __('app.edit_book_description');
            case 'library.books.show':
                return __('app.view_book_details_description');
            case 'admin.contact.index':
                return __('app.manage_contact_inquiries_description');
            case 'managing-committees.index':
                return __('app.manage_managing_committees_description');
            case 'managing-committees.create':
                return __('app.add_new_managing_committee_description');
            case 'managing-committees.edit':
                return __('app.edit_managing_committee_description');
            case 'leaves.index':
                return __('app.manage_leaves_description');
            case 'leaves.my':
                return __('app.view_my_leaves_description');
            case 'leaves.all':
                return __('app.view_all_leaves_description');
            case 'leaves.create':
                return __('app.apply_leave_description');
            case 'leaves.show':
                return __('app.view_leave_details_description');
            case 'fees.dashboard':
                return __('app.manage_fee_management_description');
            case 'fees.students':
                return __('app.view_student_fees_description');
            case 'fees.student-details':
                return __('app.view_student_fee_details_description');
            case 'fees.collect':
                return __('app.collect_fee_payment_description');
            case 'fees.reports':
                return __('app.view_fee_reports_description');
            case 'admin.gallery.index':
                return __('app.manage_gallery_description');
            case 'admin.gallery.create':
                return __('app.add_new_image_description');
            case 'admin.gallery.edit':
                return __('app.edit_image_description');
            case 'teachers.performance':
                return __('app.teacher_performance_analytics_description');
            case 'events.index':
                return __('app.manage_events_description');
            case 'events.create':
                return __('app.create_new_event_description');
            case 'events.edit':
                return __('app.edit_event_description');
            case 'admin.carousel.index':
                return __('app.manage_carousel_slides_description');
            case 'admin.carousel.create':
                return __('app.add_new_slide_description');
            case 'admin.carousel.edit':
                return __('app.edit_slide_description');
            case 'admin.contact.index':
                return __('app.manage_contact_inquiries_description');
            case 'admin.contact.show':
                return __('app.view_contact_inquiry_details_description');
            case 'managing-committees.index':
                return __('app.manage_committee_members_description');
            case 'managing-committees.create':
                return __('app.add_new_committee_member_description');
            case 'managing-committees.show':
                return __('app.view_committee_member_details_description');
            case 'managing-committees.edit':
                return __('app.edit_committee_member_description');
            case 'payroll.index':
                return __('app.manage_payroll_records_description');
            case 'payroll.create':
                return __('app.generate_payroll_description');
            case 'employee-attendance.index':
                return __('app.manage_employee_attendance_description');
            case 'employee-attendance.create':
                return __('app.mark_employee_attendance_description');
            case 'staff.index':
                return __('app.manage_staff_members_description');
            case 'staff.create':
                return __('app.add_new_staff_member_description');
            case 'reports.index':
                return __('app.reports_dashboard_description');
            case 'reports.academic-performance':
                return __('app.academic_performance_description');
            case 'reports.attendance':
                return __('app.attendance_reports_description');
            case 'reports.student-analytics':
                return __('app.student_analytics_description');
            case 'reports.teacher-performance':
                return __('app.teacher_performance_description');
            case 'reports.class-performance':
                return __('app.class_performance_description');
            case 'reports.transcripts':
                return __('app.transcripts_description');
            case 'students.transcript':
                return __('app.student_transcript_description');
            case 'reports.report-cards':
                return __('app.report_cards_description');
            case 'students.report-card':
                return __('app.student_report_card_description');
            case 'announcements.index':
                return __('app.announcements_description');
            case 'announcements.create':
                return __('app.new_announcement_description');
            case 'announcements.show':
                return __('app.announcement_details_description');
            case 'announcements.edit':
                return __('app.edit_announcement_description');
            case 'results.index':
                return __('app.results_management_description');
            case 'results.create':
                return __('app.add_result_description');
            case 'results.show':
                return __('app.result_details_description');
            case 'results.edit':
                return __('app.edit_result_description');
            case 'results.bulk-import':
                return __('app.bulk_import_description');
            case 'results.statistics':
                return __('app.statistics_description');
            case 'students.index':
                return __('app.students_description');
            case 'students.create':
                return __('app.add_student_description');
            case 'students.show':
                return __('app.student_details_description');
            case 'students.edit':
                return __('app.edit_student_description');
            case 'classes.index':
                return __('app.classes_description');
            case 'classes.create':
                return __('app.add_class_description');
            case 'classes.show':
                return __('app.class_details_description');
            case 'classes.edit':
                return __('app.edit_class_description');
            case 'subjects.index':
                return __('app.subjects_description');
            case 'subjects.create':
                return __('app.add_subject_description');
            case 'subjects.show':
                return __('app.subject_details_description');
            case 'subjects.edit':
                return __('app.edit_subject_description');
            case 'rooms.index':
                return __('app.rooms_description');
            case 'rooms.create':
                return __('app.add_room_description');
            case 'rooms.show':
                return __('app.room_details_description');
            case 'rooms.edit':
                return __('app.edit_room_description');
            case 'exams.index':
                return __('app.exams_description');
            case 'exams.create':
                return __('app.add_exam_description');
            case 'exams.show':
                return __('app.exam_details_description');
            case 'exams.edit':
                return __('app.edit_exam_description');
            case 'timetable.index':
                return __('app.timetable_description');
            case 'timetable.create':
                return __('app.add_timetable_description');
            case 'timetable.show':
                return __('app.timetable_details_description');
            case 'timetable.edit':
                return __('app.edit_timetable_description');
            case 'attendance.index':
                return __('app.attendance_description');
            case 'attendance.create':
                return __('app.mark_attendance_description');
            case 'attendance.show':
                return __('app.attendance_details_description');
            case 'parents.index':
                return __('app.parents_description');
            case 'parents.create':
                return __('app.add_parent_description');
            case 'parents.show':
                return __('app.parent_details_description');
            case 'parents.edit':
                return __('app.edit_parent_description');
            case 'teachers.dashboard':
                return __('app.teachers_dashboard_description');
            case 'teacher-scheduling.dashboard':
                return __('app.teacher_scheduling_description');
            case 'gradebook.index':
                return __('app.gradebook_description');
            default:
                return __('app.page_description_default');
        }
    }
}
