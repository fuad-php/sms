<?php

namespace App\Policies;

use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any exam results.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'student', 'parent']);
    }

    /**
     * Determine whether the user can view the exam result.
     */
    public function view(User $user, ExamResult $examResult): bool
    {
        // Admins and teachers can view all results
        if (in_array($user->role, ['admin', 'teacher'])) {
            return true;
        }

        // Students can only view their own results
        if ($user->role === 'student' && $user->student) {
            return $user->student->id === $examResult->student_id;
        }

        // Parents can view their children's results
        if ($user->role === 'parent' && $user->parent) {
            return $user->parent->students->contains($examResult->student_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create exam results.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the exam result.
     */
    public function update(User $user, ExamResult $examResult): bool
    {
        if (!in_array($user->role, ['admin', 'teacher'])) {
            return false;
        }

        // Teachers can only update results for subjects they teach
        if ($user->role === 'teacher') {
            return $user->teacher->subjects()
                ->wherePivot('class_id', $examResult->exam->class_id)
                ->wherePivot('subject_id', $examResult->exam->subject_id)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the exam result.
     */
    public function delete(User $user, ExamResult $examResult): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can restore the exam result.
     */
    public function restore(User $user, ExamResult $examResult): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can permanently delete the exam result.
     */
    public function forceDelete(User $user, ExamResult $examResult): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can bulk import results.
     */
    public function bulkImport(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can export results.
     */
    public function export(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }
}
