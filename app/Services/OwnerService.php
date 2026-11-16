<?php

namespace App\Services;

use App\Classes\Abstract\BaseUserService;
use App\Enum\TypeUser;
use Illuminate\Support\Facades\Auth;

class OwnerService extends BaseUserService
{
    public function __construct()
    {
        $this->userType = TypeUser::OWNER;
    }

    /* ========================================================================== */
    /*                                  VIEW DATA                                 */
    /* ========================================================================== */
    public static function indexViewData(): array
    {
        $company = Auth::user()->company;
        $companySystemSettignsService = new CompanySystemSettingsService($company->id);
        
        return [
            'company' => [
                'data' => $company,
                'systemSettings' => $companySystemSettignsService->get(),
                'days_to_expire_payment' => $companySystemSettignsService->getDaysToExpireDatePayment()
            ]
        ];
    }
}
