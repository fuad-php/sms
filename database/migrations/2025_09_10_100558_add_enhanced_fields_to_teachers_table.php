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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('department')->nullable()->after('designation');
            $table->string('phone_extension')->nullable()->after('department');
            $table->string('office_location')->nullable()->after('phone_extension');
            $table->text('bio')->nullable()->after('office_location');
            $table->json('certifications')->nullable()->after('bio');
            $table->json('awards')->nullable()->after('certifications');
            $table->json('research_interests')->nullable()->after('awards');
            $table->json('publications')->nullable()->after('research_interests');
            $table->integer('teaching_experience_years')->nullable()->after('publications');
            $table->json('previous_institutions')->nullable()->after('teaching_experience_years');
            $table->string('emergency_contact')->nullable()->after('previous_institutions');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
            $table->string('bank_account')->nullable()->after('emergency_phone');
            $table->string('tax_id')->nullable()->after('bank_account');
            $table->enum('contract_type', ['permanent', 'contract', 'part_time', 'visiting'])->default('permanent')->after('tax_id');
            $table->date('contract_start_date')->nullable()->after('contract_type');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->decimal('performance_rating', 3, 2)->nullable()->after('contract_end_date');
            $table->date('last_evaluation_date')->nullable()->after('performance_rating');
            $table->text('notes')->nullable()->after('last_evaluation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'department',
                'phone_extension',
                'office_location',
                'bio',
                'certifications',
                'awards',
                'research_interests',
                'publications',
                'teaching_experience_years',
                'previous_institutions',
                'emergency_contact',
                'emergency_phone',
                'bank_account',
                'tax_id',
                'contract_type',
                'contract_start_date',
                'contract_end_date',
                'performance_rating',
                'last_evaluation_date',
                'notes',
            ]);
        });
    }
};
