<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function getForUser(User $user): Collection
    {
        if ($user->hasRole(Role::TUTOR)) {
            $createdOrTutoredGroupIds = $user->createdGroups()->pluck('id')
                ->merge($user->tutoredGroups()->pluck('id'));

            return Message::whereHas('group', function ($q) use ($createdOrTutoredGroupIds) {
                $q->whereIn('id', $createdOrTutoredGroupIds);
            })->get();

        } elseif ($user->hasRole(Role::STUDENT)) {
            $userGroupIds = $user->groups()->pluck('id');

            return Message::whereHas('group', function ($q) use ($userGroupIds) {
                $q->whereIn('id', $userGroupIds);
            })->get();
        } else {
            return Message::all();
        }
    }

    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function update(Message $message, array $data): bool
    {
        return $message->update($data);
    }

    public function delete(Message $message): ?bool
    {
        return $message->delete();
    }
}
