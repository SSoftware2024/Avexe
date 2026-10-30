<?php

namespace App\Services;

use App\Classes\Abstract\BaseUserService;
use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerService extends BaseUserService
{
    public function __construct()
    {
        $this->userType = TypeUser::OWNER;
    }  
}