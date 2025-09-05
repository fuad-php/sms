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
        if (Schema::hasTable('sections')) {
            // Table exists (likely created by an earlier migration). Add missing columns safely.
            if (!Schema::hasColumn('sections', 'class_id')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->unsignedBigInteger('class_id')->after('id');
                });
            }
            if (!Schema::hasColumn('sections', 'name')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->string('name')->after('class_id');
                });
            }
            if (!Schema::hasColumn('sections', 'display_name')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->string('display_name')->nullable()->after('name');
                });
            }
            if (!Schema::hasColumn('sections', 'description')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->text('description')->nullable()->after('display_name');
                });
            }
            if (!Schema::hasColumn('sections', 'class_teacher_id')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->unsignedBigInteger('class_teacher_id')->nullable()->after('description');
                });
            }
            if (!Schema::hasColumn('sections', 'capacity')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->integer('capacity')->default(30)->after('class_teacher_id');
                });
            }
            if (!Schema::hasColumn('sections', 'start_time')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->time('start_time')->nullable()->after('capacity');
                });
            }
            if (!Schema::hasColumn('sections', 'end_time')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->time('end_time')->nullable()->after('start_time');
                });
            }
            if (!Schema::hasColumn('sections', 'is_active')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true)->after('end_time');
                });
            }

            // Add indexes/constraints if not present
            Schema::table('sections', function (Blueprint $table) {
                // Foreign keys guarded by platform support; ignore if already exist
                try {
                    $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
                } catch (\Throwable $e) {}
                try {
                    $table->foreign('class_teacher_id')->references('id')->on('users')->onDelete('set null');
                } catch (\Throwable $e) {}
            });

            // Unique index for (class_id, name)
            try {
                Schema::table('sections', function (Blueprint $table) {
                    $table->unique(['class_id', 'name']);
                });
            } catch (\Throwable $e) {}

            return;
        }

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('class_teacher_id')->nullable();
            $table->integer('capacity')->default(30);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('class_teacher_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['class_id', 'name']);
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
