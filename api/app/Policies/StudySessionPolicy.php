<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Role;
use App\Models\StudySession;
use App\Models\User;

class StudySessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read study sessions');
    }

    public function view(User $user, StudySession $studySession): bool
    {
        if (!$user->hasPermission('read study sessions')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $studySession->group->isCreatedBy($user) || $studySession->group->isTutoredBy($user);
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $studySession->group->hasMember($user);
        }

        return false;
    }

    public function create(User $user, Group $group): bool
    {
        if (!$user->hasPermission('create study sessions')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $group->isCreatedBy($user) || $group->isTutoredBy($user);
        }

        return false;
    }

    public function update(User $user, StudySession $studySession): bool
    {
        if (!$user->hasPermission('update study sessions')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $studySession->group->isCreatedBy($user) || $studySession->group->isTutoredBy($user);
        }

        return false;
    }

    public function delete(User $user, StudySession $studySession): bool
    {
        if (!$user->hasPermission('update study sessions')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $studySession->group->isCreatedBy($user) || $studySession->group->isTutoredBy($user);
        }

        return false;
    }
}