<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;

class GroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read groups');
    }

    public function view(User $user, Group $group): bool
    {
        if (!$user->hasPermission('read groups')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN) || $user->hasRole(Role::TUTOR)) {
            return true;
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $group->hasMember($user);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create groups');
    }

    public function update(User $user, Group $group): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        return $user->hasPermission('update groups') && $group->creator_id === $user->id;
    }

    public function delete(User $user, Group $group): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        return $user->hasPermission('delete groups') && $group->creator_id === $user->id;
    }
}
