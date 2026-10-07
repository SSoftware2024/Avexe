<?php

namespace App\Http\Controllers;

use App\Enum\DayOfWeek;
use App\Services\CompanyOperatingHoursService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyOperatingHoursController extends Controller
{
    public function save(Request $request)
    {
        //fazer array para colocar campos de regra dinamicamente
        $days_of_week = DayOfWeek::toArrayValues();
        $days_of_week_ptbr = DayOfWeek::toArrayPortuguese();
        $rules_days_of_week = [];
        $days_of_week_fields_ptbr = [];
        foreach ($days_of_week as $value) {
            $rules_days_of_week[] = [
                $value . '_morning_start_time' => [
                    'nullable',
                    'date_format:H:i',
                    'after_or_equal:06:00',
                    'before_or_equal:12:00'
                ],
                $value . '_morning_end_time' => [
                    'nullable',
                    'required_with:' . $value . '_morning_start_time',
                    'date_format:H:i',
                    'after:' . $value . '_morning_start_time',
                    'before_or_equal:12:00'
                ],
                $value . '_afternoon_start_time' => [
                    'nullable',
                    'date_format:H:i',
                    'after_or_equal:12:00',
                    'before_or_equal:18:00'
                ],
                $value . '_afternoon_end_time' => [
                    'nullable',
                    'required_with:' . $value . '_afternoon_start_time',
                    'date_format:H:i',
                    'after:' . $value . '_afternoon_start_time',
                    'before_or_equal:18:00'
                ],
                $value . '_evening_start_time' => [
                    'nullable',
                    'date_format:H:i',
                    'after_or_equal:18:00',
                    'before_or_equal:23:59',
                ],
                $value . '_evening_end_time' => [
                    'nullable',
                    'required_with:' . $value . '_evening_start_time',
                    'date_format:H:i',
                    'after:' . $value . '_evening_start_time',
                    'before_or_equal:23:59',
                ],
            ];
            $days_of_week_fields_ptbr[] = [
                $value . '_morning_start_time' => $days_of_week_ptbr[$value].'_turno_matutino_inicio',
                $value . '_morning_end_time' => $days_of_week_ptbr[$value].'_turno_matutino_fim',
                $value . '_afternoon_start_time' => $days_of_week_ptbr[$value].'_turno_tarde_inicio',
                $value . '_afternoon_end_time' => $days_of_week_ptbr[$value].'_turno_tarde_fim',
                $value . '_evening_start_time' => $days_of_week_ptbr[$value].'_turno_noite_inicio',
                $value . '_evening_end_time' => $days_of_week_ptbr[$value].'_turno_noite_fim',
            ];
        }
        $rules = [
            'company_id' => ['required', 'integer', Rule::exists('companies', 'id')],
        ];
        $rules = array_merge($rules, ...$rules_days_of_week);
        $request->validate($rules, [],array_merge(...$days_of_week_fields_ptbr));
        // (new CompanyOperatingHoursService($request->company_id))->save($request->all());
    }
}
