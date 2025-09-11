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
        Schema::create('teacher_performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('evaluator_id')->nullable();
            $table->string('evaluation_type')->default('annual'); // annual, quarterly, monthly, special
            $table->string('academic_year');
            $table->string('semester')->nullable();
            $table->date('evaluation_date');
            $table->date('period_start');
            $table->date('period_end');
            
            // Performance ratings (1-5 scale)
            $table->decimal('teaching_quality', 3, 2)->default(0);
            $table->decimal('classroom_management', 3, 2)->default(0);
            $table->decimal('student_engagement', 3, 2)->default(0);
            $table->decimal('subject_knowledge', 3, 2)->default(0);
            $table->decimal('communication_skills', 3, 2)->default(0);
            $table->decimal('professionalism', 3, 2)->default(0);
            $table->decimal('punctuality', 3, 2)->default(0);
            $table->decimal('collaboration', 3, 2)->default(0);
            $table->decimal('innovation', 3, 2)->default(0);
            $table->decimal('student_feedback', 3, 2)->default(0);
            
            // Overall performance
            $table->decimal('overall_rating', 3, 2)->default(0);
            $table->string('performance_level')->default('needs_improvement'); // excellent, good, satisfactory, needs_improvement, poor
            
            // Additional metrics
            $table->integer('classes_taught')->default(0);
            $table->integer('students_taught')->default(0);
            $table->decimal('average_student_grade', 5, 2)->nullable();
            $table->integer('attendance_rate')->default(100); // percentage
            $table->integer('punctuality_rate')->default(100); // percentage
            
            // Feedback and comments
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('evaluator_comments')->nullable();
            $table->text('teacher_response')->nullable();
            
            // Status and approval
            $table->string('status')->default('draft'); // draft, submitted, under_review, approved, rejected
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Goals and objectives
            $table->json('goals')->nullable(); // JSON array of goals for next period
            $table->json('achievements')->nullable(); // JSON array of achieved goals
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');
            $table->foreign('evaluator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['teacher_id', 'academic_year']);
            $table->index(['evaluation_type', 'status']);
            $table->index('evaluation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_performances');
    }
};