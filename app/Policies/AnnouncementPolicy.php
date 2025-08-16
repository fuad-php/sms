<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any announcements.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'student', 'parent']);
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        // Admin can view all announcements
        if ($user->role === 'admin') {
            return true;
        }

        // Check if user has access to this announcement based on target audience and class
        if ($user->role === 'teacher') {
            // Teachers can view announcements for their classes and general announcements
            $teacherClasses = $user->teacher->classes->pluck('id');
            return is_null($announcement->class_id) || $teacherClasses->contains($announcement->class_id);
        }

        if ($user->role === 'student') {
            // Students can view announcements for their class and general announcements
            $studentClass = $user->student->class_id;
            $hasClassAccess = is_null($announcement->class_id) || $announcement->class_id === $studentClass;
            $hasAudienceAccess = in_array($announcement->target_audience, ['all', 'students']);
            return $hasClassAccess && $hasAudienceAccess;
        }

        if ($user->role === 'parent') {
            // Parents can view announcements for their children's classes and general announcements
            $childrenClasses = $user->parent->students->pluck('class_id');
            $hasClassAccess = is_null($announcement->class_id) || $childrenClasses->contains($announcement->class_id);
            $hasAudienceAccess = in_array($announcement->target_audience, ['all', 'parents']);
            return $hasClassAccess && $hasAudienceAccess;
        }

        return false;
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        // Admin can update any announcement
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can update announcements they created
        if ($user->role === 'teacher') {
            return $announcement->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        // Admin can delete any announcement
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can delete announcements they created
        if ($user->role === 'teacher') {
            return $announcement->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the announcement.
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the announcement.
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $user->role === 'admin';
    }
}
