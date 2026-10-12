<?php

namespace App\Services;

use App\Classes\Abstract\CompanySettingsService;
use App\Models\CompanySystemSetting;
use Override;

final class CompanySystemSettingsService extends CompanySettingsService
{
    protected function getModel()
    {
        return CompanySystemSetting::class;
    }

    #[Override]
    public function save(array $data)
    {
        parent::save([
            'simultaneous_access' => $data['simultaneous_access'],
            'max_owners' => $data['max_owners'],
            'max_employees' => $data['max_employees'],
            'date_payment' => date('Y-m-d', strtotime($data['date_payment'])),
            'interval_return_message' => $data['interval_return_message'],
            'value_monthly_fee' => $data['value_monthly_fee'],
        ]);
    }
}
