<?php

namespace App\Services;

use App\Models\User;

final class DeveloperService
{
    private OwnerService $ownerService;
    private CompanyService $companyService;

    public function __construct()
    {
        $this->ownerService = new OwnerService();
        $this->companyService = new CompanyService();
    }

}
