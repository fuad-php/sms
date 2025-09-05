<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'designation')) {
                $table->string('designation')->nullable()->after('employee_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'designation')) {
                $table->dropColumn('designation');
            }
        });
    }
};


