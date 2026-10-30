<?php

namespace App\Policies;

use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function accessDeveloper(User $user)
    {
        return $user->user_type == TypeUser::DEVELOPER->value;
    }
}
