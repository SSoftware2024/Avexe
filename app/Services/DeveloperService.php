<?php

namespace App\Services;

use App\Classes\Abstract\BaseUserService;
use App\Enum\TypeUser;
use App\Models\Company;
use App\Models\User;
use App\Services\CompanyService;

final class DeveloperService extends BaseUserService
{
    public function __construct()
    {
        $this->userType = TypeUser::DEVELOPER;
    }  
}
