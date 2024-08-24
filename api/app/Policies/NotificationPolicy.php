<?php

namespace App\Policies;

use App\Models\User;

class NotificationPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermission('view notifications');
    }
}
