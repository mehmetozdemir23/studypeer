<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function getForUser(User $user): Collection
    {
        return $user->hasRole(Role::STUDENT) ? $user->groups : Group::all();
    }

    public function create(array $data)
    {
        $group = Group::create($data);

        $group->tutors()->attach(request()->user()->id, ['role' => 'tutor']);

        return $group;
    }

    public function update(Group $group, array $data): bool
    {
        return $group->update($data);
    }

    public function delete(Group $group): bool|null
    {
        return $group->delete();
    }
}