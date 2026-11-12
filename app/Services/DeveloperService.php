<?php

namespace App\Services;

use App\Classes\Abstract\BaseUserService;
use App\Enum\TypeUser;
use App\Services\CompanyService;

final class DeveloperService extends BaseUserService
{
    public function __construct()
    {
        $this->userType = TypeUser::DEVELOPER;
    }
    /* ========================================================================== */
    /*                                  VIEW DATA                                 */
    /* ========================================================================== */
    public function dashboardDataView():array
    {
        //empresas ativas e inativas
        $date_now = now();
        $monthlyAmount = CompanySystemSettingsService::s_monthlyAmount($date_now);
        $companies = [
            'active' => CompanyService::s_count('active','=', 1),
            'inactive' => CompanyService::s_count('active','=', 0),
            'monthlyAmount' => [
                'count' => $monthlyAmount['count'],
                'value_total_month' => getMoneyToStringBr($monthlyAmount['value_total_month'])
            ],
            'expired' => CompanySystemSettingsService::s_dateExpired($date_now)
        ];
        $owners = [
            'active' => (new OwnerService())->count('active','=', 1),
            'inactive' => (new OwnerService())->count('active','=', 0)
        ];
        //fazer url de filtro
        return compact('companies', 'owners');
    }
}
