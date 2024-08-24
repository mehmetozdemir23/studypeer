<?php

namespace App\Services;

use App\Models\Role;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class StudySessionService
{
    public function getForUser(User $user): Collection
    {
        if ($user->hasRole(Role::TUTOR)) {
            $createdOrTutoredGroupIds = $user->createdGroups()->pluck('id')
                ->merge($user->tutoredGroups()->pluck('id'));

            return StudySession::whereHas('group', function ($q) use ($createdOrTutoredGroupIds) {
                $q->whereIn('id', $createdOrTutoredGroupIds);
            })->get();

        } elseif ($user->hasRole(Role::STUDENT)) {
            $userGroupIds = $user->groups()->pluck('id');

            return StudySession::whereHas('group', function ($q) use ($userGroupIds) {
                $q->whereIn('id', $userGroupIds);
            })->get();
        } else {
            return StudySession::all();
        }
    }

    public function create(array $data): StudySession
    {
        return StudySession::create($data);
    }

    public function update(StudySession $studySession, array $data): bool
    {
        return $studySession->update($data);
    }

    public function delete(StudySession $studySession): ?bool
    {
        return $studySession->delete();
    }
}
