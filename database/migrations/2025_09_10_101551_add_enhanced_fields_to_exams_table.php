<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('academic_year')->nullable()->after('exam_type');
            $table->string('exam_session')->nullable()->after('academic_year');
            $table->string('exam_venue')->nullable()->after('exam_session');
            $table->text('instructions')->nullable()->after('exam_venue');
            $table->integer('duration_minutes')->nullable()->after('instructions');
            $table->decimal('weightage', 5, 2)->default(100.00)->after('duration_minutes');
            $table->boolean('is_online')->default(false)->after('weightage');
            $table->integer('max_attempts')->default(1)->after('is_online');
            $table->boolean('late_submission_allowed')->default(false)->after('max_attempts');
            $table->decimal('late_submission_penalty', 5, 2)->default(0.00)->after('late_submission_allowed');
            $table->unsignedBigInteger('created_by')->nullable()->after('late_submission_penalty');
            $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            $table->date('approval_date')->nullable()->after('approved_by');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('draft')->after('approval_date');
            $table->string('grade_scale')->default('A+,A,A-,B+,B,B-,C+,C,C-,D,F')->after('status');
            $table->boolean('negative_marking')->default(false)->after('grade_scale');
            $table->decimal('negative_marking_ratio', 3, 2)->default(0.25)->after('negative_marking');
            $table->string('exam_code')->unique()->nullable()->after('negative_marking_ratio');
            $table->boolean('is_archived')->default(false)->after('exam_code');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'academic_year',
                'exam_session',
                'exam_venue',
                'instructions',
                'duration_minutes',
                'weightage',
                'is_online',
                'max_attempts',
                'late_submission_allowed',
                'late_submission_penalty',
                'created_by',
                'approved_by',
                'approval_date',
                'status',
                'grade_scale',
                'negative_marking',
                'negative_marking_ratio',
                'exam_code',
                'is_archived',
            ]);
        });
    }
};
