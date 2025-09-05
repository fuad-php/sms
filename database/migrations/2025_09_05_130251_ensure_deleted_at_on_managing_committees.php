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
        Schema::table('managing_committees', function (Blueprint $table) {
            if (!Schema::hasColumn('managing_committees', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('managing_committees', function (Blueprint $table) {
            if (Schema::hasColumn('managing_committees', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
