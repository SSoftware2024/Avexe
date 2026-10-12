<?php

namespace App\Http\Controllers;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Services\CompanySystemSettingsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanySettingsController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'company_id' => ['required', 'integer', Rule::exists('companies', 'id')],
            'simultaneous_access' => ['required', 'integer', 'min:1'],
            'max_owners' => ['required', 'integer', 'min:1'],
            'max_employees' => ['required', 'integer', 'min:1'],
            'date_payment' => ['required', 'date'],
            'interval_return_message' => ['required', 'integer', 'min:1', 'max:30'],
            'value_monthly_fee' => ['required', 'numeric']
        ]);
        (new CompanySystemSettingsService($request->company_id))->save($request->all());
        Toast::success('Operação realizada com sucesso');
    }
}
