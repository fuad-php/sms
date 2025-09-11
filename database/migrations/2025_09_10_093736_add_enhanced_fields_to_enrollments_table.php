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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('enrollment_number')->unique()->after('id');
            $table->boolean('is_active')->default(true)->after('status');
            $table->unsignedBigInteger('promoted_from')->nullable()->after('is_active');
            $table->unsignedBigInteger('transferred_to')->nullable()->after('promoted_from');
            $table->string('withdrawal_reason')->nullable()->after('transferred_to');
            $table->date('withdrawal_date')->nullable()->after('withdrawal_reason');
            $table->softDeletes();
            
            $table->foreign('promoted_from')->references('id')->on('enrollments')->onDelete('set null');
            $table->foreign('transferred_to')->references('id')->on('enrollments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['promoted_from']);
            $table->dropForeign(['transferred_to']);
            $table->dropColumn([
                'enrollment_number',
                'is_active',
                'promoted_from',
                'transferred_to',
                'withdrawal_reason',
                'withdrawal_date',
                'deleted_at'
            ]);
        });
    }
};
