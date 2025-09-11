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
        Schema::table('parents', function (Blueprint $table) {
            $table->enum('parent_type', ['father', 'mother', 'guardian', 'stepfather', 'stepmother', 'grandparent', 'other'])->default('guardian')->after('user_id');
            $table->enum('education_level', ['primary', 'secondary', 'high_school', 'diploma', 'bachelor', 'master', 'phd'])->nullable()->after('parent_type');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'separated'])->nullable()->after('education_level');
            $table->string('emergency_contact_name')->nullable()->after('marital_status');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
            $table->string('preferred_language')->default('en')->after('emergency_contact_relation');
            $table->enum('communication_preference', ['email', 'phone', 'sms', 'whatsapp', 'in_person'])->default('email')->after('preferred_language');
            $table->boolean('is_primary_contact')->default(false)->after('communication_preference');
            $table->boolean('can_pickup_student')->default(true)->after('is_primary_contact');
            $table->json('authorized_pickup_persons')->nullable()->after('can_pickup_student');
            $table->boolean('medical_consent')->default(false)->after('authorized_pickup_persons');
            $table->boolean('photo_consent')->default(false)->after('medical_consent');
            $table->boolean('data_sharing_consent')->default(false)->after('photo_consent');
            $table->date('last_contact_date')->nullable()->after('data_sharing_consent');
            $table->text('communication_notes')->nullable()->after('last_contact_date');
            $table->string('parent_id_number')->nullable()->after('communication_notes');
            $table->string('parent_id_type')->nullable()->after('parent_id_number');
            $table->string('address_line_2')->nullable()->after('parent_id_type');
            $table->string('city')->nullable()->after('address_line_2');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->after('postal_code');
            $table->string('alternate_phone')->nullable()->after('country');
            $table->string('alternate_email')->nullable()->after('alternate_phone');
            $table->json('social_media_handles')->nullable()->after('alternate_email');
            $table->string('preferred_contact_time')->nullable()->after('social_media_handles');
            $table->text('special_instructions')->nullable()->after('preferred_contact_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn([
                'parent_type',
                'education_level',
                'marital_status',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
                'preferred_language',
                'communication_preference',
                'is_primary_contact',
                'can_pickup_student',
                'authorized_pickup_persons',
                'medical_consent',
                'photo_consent',
                'data_sharing_consent',
                'last_contact_date',
                'communication_notes',
                'parent_id_number',
                'parent_id_type',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country',
                'alternate_phone',
                'alternate_email',
                'social_media_handles',
                'preferred_contact_time',
                'special_instructions',
            ]);
        });
    }
};
