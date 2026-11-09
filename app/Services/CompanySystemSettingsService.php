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

    public static function s_monthlyAmount(string $month): array
    {
        $time = strtotime($month);
        $companySystemSettings = CompanySystemSetting::select('date_payment', 'value_monthly_fee')
            ->whereHas('company', fn($query) => $query->where('active', 1))
            ->whereMonth('date_payment', date('m', $time))
            ->whereYear('date_payment', date('Y', $time));
        return [
            'count' => $companySystemSettings->count(),
            'value_total_month' => $companySystemSettings->sum('value_monthly_fee')
        ];
    }
    public static function s_dateExpired(string $date): array
    {
        $companySystemSettings = CompanySystemSetting::select('company_id', 'date_payment')
            ->whereDate('date_payment', '<', date('Y-m-d', strtotime($date)));
        return [
            'count' => $companySystemSettings->count(),
            'company_ids' => $companySystemSettings->get('company_id')
        ];
    }
}
