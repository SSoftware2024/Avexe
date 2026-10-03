<?php

namespace App\Services;

use App\Models\Company;
use App\Services\CompanyService;

final class DeveloperService
{
    private CompanyService $companyService;

    public function __construct()
    {
        $this->companyService = new CompanyService();
    }




    # ================================================================================= #
    #                                    PRIVADOS
    # ================================================================================= #


}
