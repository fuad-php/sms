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
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('issued_by'); // librarian who issued the book
            $table->unsignedBigInteger('returned_by')->nullable(); // librarian who received the return
            
            // Issue details
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            
            // Status and tracking
            $table->enum('status', ['issued', 'returned', 'overdue', 'lost', 'damaged', 'renewed'])->default('issued');
            $table->integer('renewal_count')->default(0);
            $table->integer('max_renewals')->default(2);
            $table->date('last_renewal_date')->nullable();
            
            // Fines and penalties
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->decimal('penalty_amount', 8, 2)->default(0);
            $table->decimal('total_fine', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->date('fine_paid_date')->nullable();
            $table->text('fine_notes')->nullable();
            
            // Condition tracking
            $table->enum('issue_condition', ['excellent', 'good', 'fair', 'poor'])->default('excellent');
            $table->enum('return_condition', ['excellent', 'good', 'fair', 'poor', 'damaged'])->nullable();
            $table->text('condition_notes')->nullable();
            $table->text('damage_description')->nullable();
            $table->decimal('damage_penalty', 8, 2)->default(0);
            
            // Notifications and reminders
            $table->boolean('reminder_sent')->default(false);
            $table->date('last_reminder_date')->nullable();
            $table->integer('reminder_count')->default(0);
            $table->boolean('overdue_notification_sent')->default(false);
            
            // Academic information
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
            $table->string('purpose')->nullable(); // study, research, reference, etc.
            $table->text('notes')->nullable();
            
            // Digital tracking
            $table->string('barcode')->nullable();
            $table->string('qr_code')->nullable();
            $table->boolean('is_digital_copy')->default(false);
            $table->string('digital_access_url')->nullable();
            $table->timestamp('digital_access_expires_at')->nullable();
            
            // Approval workflow
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            // Reservation system
            $table->boolean('is_reservation')->default(false);
            $table->date('reservation_date')->nullable();
            $table->date('reservation_expires_at')->nullable();
            $table->boolean('reservation_fulfilled')->default(false);
            
            // Statistics
            $table->integer('days_issued')->default(0);
            $table->integer('days_overdue')->default(0);
            $table->boolean('is_extended')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('returned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['book_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['teacher_id', 'status']);
            $table->index(['issue_date']);
            $table->index(['due_date']);
            $table->index(['return_date']);
            $table->index(['status', 'due_date']);
            $table->index(['academic_year', 'semester']);
            $table->index(['is_reservation', 'reservation_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
