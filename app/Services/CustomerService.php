<?php

namespace App\Services;

use App\Models\User;

final class CustomerService
{

    public function removeProfile(User $user)
    {
        $user->removeProfile();
        $user->save();
    }
}
