<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
class UserService
{
    public function getForUser(User $user): Collection
    {
        if ($user->hasRole(Role::TUTOR)) {
            return $user->membersOfTutoredGroups();
        } elseif ($user->hasRole(Role::STUDENT)) {
            return $user->usersInSameGroups();
        } else {
            return User::all();
        }
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool|null
    {
        return $user->delete();
    }
}