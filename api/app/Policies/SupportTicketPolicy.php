<?php

namespace App\Policies;

use App\Models\User;

class SupportTicketPolicy
{
    public function submit(User $user): bool
    {
        return $user->hasPermission('submit support tickets');
    }
}
