<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Role;
use App\Models\SharedFile;
use App\Models\User;

class SharedFilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read shared files');
    }

    public function view(User $user, SharedFile $sharedFile): bool
    {
        if (! $user->hasPermission('read shared files')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $sharedFile->group->isCreatedBy($user) || $sharedFile->group->isTutoredBy($user);
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $sharedFile->group->hasMember($user);
        }

        return false;
    }

    public function create(User $user, Group $group): bool
    {
        if (! $user->hasPermission('create shared files')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $group->isCreatedBy($user) || $group->isTutoredBy($user);
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $group->hasMember($user);
        }

        return false;
    }

    public function update(User $user, SharedFile $sharedFile): bool
    {
        if (! $user->hasPermission('update shared files')) {
            return false;
        }

        if ($sharedFile->uploader_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, SharedFile $sharedFile): bool
    {
        if (! $user->hasPermission('delete shared files')) {
            return false;
        }

        if ($sharedFile->uploader_id === $user->id) {
            return true;
        }

        return false;
    }
}
