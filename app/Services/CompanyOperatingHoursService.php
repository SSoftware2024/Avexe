<?php

namespace App\Services;

use App\Classes\Abstract\CompanySettingsService;
use App\Enum\DayOfWeek;
use App\Models\CompanyOperatingHours;
use Override;

final class CompanyOperatingHoursService extends CompanySettingsService
{
    protected function getModel()
    {
        return CompanyOperatingHours::class;
    }
    #[Override]
    public function save(array $data)
    {
        $days_of_week = DayOfWeek::toArrayValues();
        foreach ($days_of_week as $value) {
            parent::save([
                'day_of_week' => $value,
                'is_active' => $data[$value.'_is_active'],
                'morning_start_time' => $data[$value.'_morning_start_time'],
                'morning_end_time' => $data[$value.'_morning_end_time'],
                'afternoon_start_time' => $data[$value.'_afternoon_start_time'],
                'afternoon_end_time' => $data[$value.'_afternoon_end_time'],
                'evening_start_time' => $data[$value.'_evening_start_time'],
                'evening_end_time' => $data[$value.'_evening_end_time'],
            ]);
        }
    }
}
