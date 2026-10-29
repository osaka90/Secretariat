<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MinisterPublicRegister;
use Illuminate\Auth\Access\HandlesAuthorization;

class MinisterPublicRegisterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MinisterPublicRegister');
    }

    public function view(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('View:MinisterPublicRegister');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MinisterPublicRegister');
    }

    public function update(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('Update:MinisterPublicRegister');
    }

    public function delete(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('Delete:MinisterPublicRegister');
    }

    public function restore(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('Restore:MinisterPublicRegister');
    }

    public function forceDelete(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('ForceDelete:MinisterPublicRegister');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MinisterPublicRegister');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MinisterPublicRegister');
    }

    public function replicate(AuthUser $authUser, MinisterPublicRegister $ministerPublicRegister): bool
    {
        return $authUser->can('Replicate:MinisterPublicRegister');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MinisterPublicRegister');
    }

}