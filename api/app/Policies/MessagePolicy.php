<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Message;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read messages');
    }

    public function view(User $user, Message $message): bool
    {
        if (!$user->hasPermission('read messages')) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::TUTOR)) {
            return $message->group->isCreatedBy($user) || $message->group->isTutoredBy($user);
        }

        if ($user->hasRole(Role::STUDENT)) {
            return $message->group->hasMember($user);
        }

        return false;
    }

    public function create(User $user, Group $group): bool
    {
        if (!$user->hasPermission('create messages')) {
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

    public function update(User $user, Message $message): bool
    {
        if (!$user->hasPermission('update messages')) {
            return false;
        }

        if ($message->sender_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Message $message): bool
    {
        if (!$user->hasPermission('delete messages')) {
            return false;
        }

        if ($message->sender_id === $user->id) {
            return true;
        }

        return false;
    }
}
