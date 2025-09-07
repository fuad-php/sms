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
        Schema::table('timetables', function (Blueprint $table) {
            if (!Schema::hasColumn('timetables', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
                // sections table exists earlier in the migration chain; safe to add FK
                try { $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete(); } catch (\Throwable $e) {}
            }

            if (!Schema::hasColumn('timetables', 'room_id')) {
                $table->unsignedBigInteger('room_id')->nullable()->after('end_time');
                // rooms table may not exist yet on fresh migrations; guard FK creation
                if (Schema::hasTable('rooms')) {
                    try { $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete(); } catch (\Throwable $e) {}
                }
            }

            // Optional indexes to speed lookups
            try { $table->index(['class_id', 'section_id', 'day_of_week']); } catch (\Throwable $e) {}
            try { $table->index(['teacher_id', 'day_of_week']); } catch (\Throwable $e) {}
            try { $table->index(['room_id', 'day_of_week']); } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn('timetables', 'room_id')) {
                try { $table->dropIndex(['room_id', 'day_of_week']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['room_id']); } catch (\Throwable $e) {}
                $table->dropColumn('room_id');
            }
            if (Schema::hasColumn('timetables', 'section_id')) {
                try { $table->dropIndex(['class_id', 'section_id', 'day_of_week']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['section_id']); } catch (\Throwable $e) {}
                $table->dropColumn('section_id');
            }
        });
    }
};
