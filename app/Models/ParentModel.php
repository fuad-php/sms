<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'occupation',
        'workplace',
        'income_range',
        'notes',
        'is_active',
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary_contact' => 'boolean',
        'can_pickup_student' => 'boolean',
        'medical_consent' => 'boolean',
        'photo_consent' => 'boolean',
        'data_sharing_consent' => 'boolean',
        'last_contact_date' => 'date',
        'authorized_pickup_persons' => 'array',
        'social_media_handles' => 'array',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relationship')
                    ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper methods
     */
    public function getChildrenCount()
    {
        return $this->students()->count();
    }

    public function getAllChildrenAttendance($startDate = null, $endDate = null)
    {
        $attendance = [];
        foreach ($this->students as $student) {
            $attendance[$student->id] = [
                'student' => $student,
                'percentage' => $student->getAttendancePercentage($startDate, $endDate),
            ];
        }
        return $attendance;
    }

    public function getAllChildrenGrades()
    {
        $grades = [];
        foreach ($this->students as $student) {
            $grades[$student->id] = [
                'student' => $student,
                'recent_grades' => $student->examResults()
                                         ->with('exam')
                                         ->latest()
                                         ->limit(5)
                                         ->get(),
                'average_grade' => $student->examResults()
                                          ->avg('marks_obtained'),
            ];
        }
        return $grades;
    }

    /**
     * Enhanced Scopes
     */
    public function scopeByParentType($query, $type)
    {
        return $query->where('parent_type', $type);
    }

    public function scopePrimaryContacts($query)
    {
        return $query->where('is_primary_contact', true);
    }

    public function scopeCanPickup($query)
    {
        return $query->where('can_pickup_student', true);
    }

    public function scopeByEducationLevel($query, $level)
    {
        return $query->where('education_level', $level);
    }

    public function scopeByMaritalStatus($query, $status)
    {
        return $query->where('marital_status', $status);
    }

    public function scopeWithConsent($query, $consentType)
    {
        return $query->where($consentType, true);
    }

    /**
     * Enhanced Helper Methods
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function getPhoneAttribute()
    {
        return $this->user->phone;
    }

    public function getAddressAttribute()
    {
        return $this->user->address;
    }

    public function getFullAddress()
    {
        $address = $this->user->address;
        if ($this->address_line_2) {
            $address .= ', ' . $this->address_line_2;
        }
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        if ($this->postal_code) {
            $address .= ' ' . $this->postal_code;
        }
        if ($this->country) {
            $address .= ', ' . $this->country;
        }
        return $address;
    }

    public function getParentTypeBadgeClass()
    {
        return match($this->parent_type) {
            'father' => 'bg-blue-100 text-blue-800',
            'mother' => 'bg-pink-100 text-pink-800',
            'guardian' => 'bg-green-100 text-green-800',
            'stepfather' => 'bg-purple-100 text-purple-800',
            'stepmother' => 'bg-indigo-100 text-indigo-800',
            'grandparent' => 'bg-yellow-100 text-yellow-800',
            'other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getMaritalStatusBadgeClass()
    {
        return match($this->marital_status) {
            'single' => 'bg-gray-100 text-gray-800',
            'married' => 'bg-green-100 text-green-800',
            'divorced' => 'bg-red-100 text-red-800',
            'widowed' => 'bg-purple-100 text-purple-800',
            'separated' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getEducationLevelBadgeClass()
    {
        return match($this->education_level) {
            'primary' => 'bg-red-100 text-red-800',
            'secondary' => 'bg-orange-100 text-orange-800',
            'high_school' => 'bg-yellow-100 text-yellow-800',
            'diploma' => 'bg-blue-100 text-blue-800',
            'bachelor' => 'bg-green-100 text-green-800',
            'master' => 'bg-purple-100 text-purple-800',
            'phd' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCommunicationPreferenceBadgeClass()
    {
        return match($this->communication_preference) {
            'email' => 'bg-blue-100 text-blue-800',
            'phone' => 'bg-green-100 text-green-800',
            'sms' => 'bg-yellow-100 text-yellow-800',
            'whatsapp' => 'bg-green-100 text-green-800',
            'in_person' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getActiveChildrenCount()
    {
        return $this->students()->where('is_active', true)->count();
    }

    public function getChildrenInClass($classId)
    {
        return $this->students()
                    ->where('class_id', $classId)
                    ->where('is_active', true)
                    ->get();
    }

    public function getChildrenByStatus($status)
    {
        return $this->students()
                    ->where('is_active', $status === 'active')
                    ->get();
    }

    public function getRecentCommunication()
    {
        // This would typically come from a communications table
        return [
            'last_contact' => $this->last_contact_date,
            'communication_notes' => $this->communication_notes,
            'preferred_time' => $this->preferred_contact_time,
        ];
    }

    public function getConsentStatus()
    {
        return [
            'medical' => $this->medical_consent,
            'photo' => $this->photo_consent,
            'data_sharing' => $this->data_sharing_consent,
        ];
    }

    public function getPickupAuthorization()
    {
        return [
            'can_pickup' => $this->can_pickup_student,
            'authorized_persons' => $this->authorized_pickup_persons ?? [],
        ];
    }

    public function getContactInfo()
    {
        return [
            'primary_phone' => $this->getPhoneAttribute(),
            'alternate_phone' => $this->alternate_phone,
            'primary_email' => $this->getEmailAttribute(),
            'alternate_email' => $this->alternate_email,
            'preferred_language' => $this->preferred_language,
            'communication_preference' => $this->communication_preference,
            'preferred_time' => $this->preferred_contact_time,
        ];
    }

    public function getEmergencyContacts()
    {
        return [
            'name' => $this->emergency_contact_name,
            'phone' => $this->emergency_contact_phone,
            'relation' => $this->emergency_contact_relation,
        ];
    }

    public function isPrimaryContact()
    {
        return $this->is_primary_contact;
    }

    public function canPickupStudent()
    {
        return $this->can_pickup_student;
    }

    public function hasConsent($consentType)
    {
        return $this->$consentType ?? false;
    }

    public function getRelationshipToStudent($studentId)
    {
        $pivot = $this->students()->where('student_id', $studentId)->first();
        return $pivot ? $pivot->pivot->relationship : null;
    }

    public function updateLastContact()
    {
        $this->update(['last_contact_date' => now()]);
    }
}