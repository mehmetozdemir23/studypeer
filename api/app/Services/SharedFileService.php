<?php

namespace App\Services;

use App\Models\Role;
use App\Models\SharedFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Storage;

class SharedFileService
{
    public function getForUser(User $user): Collection
    {
        if ($user->hasRole(Role::TUTOR)) {
            $createdOrTutoredGroupIds = $user->createdGroups()->pluck('id')
                ->merge($user->tutoredGroups()->pluck('id'));

            return SharedFile::whereHas('group', function ($q) use ($createdOrTutoredGroupIds) {
                $q->whereIn('id', $createdOrTutoredGroupIds);
            })->get();

        } elseif ($user->hasRole(Role::STUDENT)) {
            $userGroupIds = $user->groups()->pluck('id');

            return SharedFile::whereHas('group', function ($q) use ($userGroupIds) {
                $q->whereIn('id', $userGroupIds);
            })->get();
        } else {
            return SharedFile::all();
        }
    }

    public function create(array $data): SharedFile
    {
        $sharedFile = new SharedFile($data);

        $sharedFile->uploader_id = Auth::id();

        if (isset($data['file'])) {
            $sharedFile->file_path = $data['file']->store('shared_files');
        }

        $sharedFile->save();

        return $sharedFile;
    }

    public function update(SharedFile $sharedFile, array $data): bool
    {
        return $sharedFile->update($data);
    }

    public function delete(SharedFile $sharedFile): ?bool
    {
        Storage::delete($sharedFile->file_path);

        return $sharedFile->delete();
    }
}
