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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Grade 1", "Class 10"
            $table->string('section')->nullable(); // e.g., "A", "B", "C"
            $table->text('description')->nullable();
            $table->unsignedBigInteger('class_teacher_id')->nullable();
            $table->integer('capacity')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('class_teacher_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['name', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};