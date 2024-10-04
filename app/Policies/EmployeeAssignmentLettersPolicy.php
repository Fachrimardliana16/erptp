<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EmployeeAssignmentLetters;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeAssignmentLettersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_employee::assignment::letters');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('view_employee::assignment::letters');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_employee::assignment::letters');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('update_employee::assignment::letters');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('delete_employee::assignment::letters');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_employee::assignment::letters');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('force_delete_employee::assignment::letters');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_employee::assignment::letters');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('restore_employee::assignment::letters');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_employee::assignment::letters');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, EmployeeAssignmentLetters $employeeAssignmentLetters): bool
    {
        return $user->can('replicate_employee::assignment::letters');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_employee::assignment::letters');
    }
}
