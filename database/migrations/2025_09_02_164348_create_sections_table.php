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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('name'); // e.g., "A", "B", "C"
            $table->string('display_name')->nullable(); // e.g., "Section A", "Morning Section"
            $table->text('description')->nullable();
            $table->unsignedBigInteger('class_teacher_id')->nullable();
            $table->integer('capacity')->default(30);
            $table->time('start_time')->nullable(); // For different timing sections
            $table->time('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('class_teacher_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['class_id', 'name']); // Unique section name per class
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
