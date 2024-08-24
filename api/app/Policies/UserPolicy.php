<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read users');
    }

    public function view(User $user, User $model): bool
    {
        if (!$user->hasPermission('read users')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $user->isTutorOf($model);
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $user->sharesGroupWith($model);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create users');
    }

    public function update(User $user, User $model): bool
    {
        if (!$user->hasPermission('update users')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $user->isTutorOf($model);
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('delete users');
    }
}
