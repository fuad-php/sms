<?php

namespace App\Policies;

use App\Models\ManagingCommittee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManagingCommitteePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'teacher', 'parent', 'student', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManagingCommittee $managingCommittee): bool
    {
        return $user->hasRole(['admin', 'teacher', 'parent', 'student', 'staff']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ManagingCommittee $managingCommittee): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManagingCommittee $managingCommittee): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ManagingCommittee $managingCommittee): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ManagingCommittee $managingCommittee): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage committees.
     */
    public function manageCommittees(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
