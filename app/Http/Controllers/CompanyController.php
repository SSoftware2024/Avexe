<?php

namespace App\Http\Controllers;

use App\Facades\DialogAlert;
use App\Facades\Toast;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CompanyController extends Controller
{
    # ================================================================================= #
    #                                    Empresa
    # ================================================================================= #
    public function listView(Request $request, CompanyService $service)
    {
        $request->validate([
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
            'sort_by' => ['nullable', 'array']
        ]);
        $per_page = $request->has('per_page') ? $request->per_page : 10;
        $sort_by = $request->has('sort_by') ? $request->sort_by : [];
        $companies = $service->listViewData($per_page, $sort_by, $request->input('filter', []));
        return Inertia::render('Dev/CompanyList', [
            'companies' => $companies
        ]);
    }
    public function createOrUpdateView(
        Request $request,
        CompanyService $service,
        ?int $id = null
    ) {
        $data = $service->createOrUpdateViewData($id);
        return Inertia::render('Dev/CompanyCreateUpdate', $data);
    }

    public function createOrUpdate(
        Request $request,
        CompanyService $service,
    ) {
        $cpf_or_cnpj_is_required = (empty($request->cpf) && empty($request->cnpj)) ? 'required' : 'nullable';
        $id = $request->id;
        $request->validate([
            'id' => ['nullable', 'integer', Rule::exists('companies', 'id')],
            'owners_ids' => ['required', 'array'],
            'name' => ['required'],
            'corporate_name' => ['required', 'max:255'],
            'cnpj' => [$cpf_or_cnpj_is_required], //cnpj
            'cpf' => [$cpf_or_cnpj_is_required], //cpf
            'tag_url' => [
                'required',
                'lowercase',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                is_null($id) ? Rule::unique(Company::class) : Rule::unique(Company::class)->ignore($id)
            ],
            'old_owners_id' => [is_null($id) ? 'nullable' : 'required', 'array'],
            'old_owners_id.*' => ['integer']
        ], [
            'cnpj.required' => 'Informe CNPJ ou CPF',
            'cpf.required' => 'Informe CNPJ ou CPF',
            'tag_url.regex' => 'Sem espaço, apenas letras, números e _'
        ]);
        $data = $request->all();
        if (empty($data['id'])) {
            $company = $service->create($data, $data['owners_ids']);
            Toast::success('Empresa cadastrada com sucesso!');
            Toast::info('Tela de edição empresa');
            return redirect()->route('company.createOrUpdateView', [
                'id' => $company->id
            ]);
        } else {
            $total_rows = $service->updateByDeveloper($data['id'], $data, $data['old_owners_id']);
            Toast::success("$total_rows registros atualizados com sucesso");
        }

        
    }

    public function toggleActive(Request $request, CompanyService $service)
    {
        $request->validate([
            'id' => ['required', 'integer', Rule::exists('companies', 'id')]
        ]);
        $service->toggleActive($request->id);
    }

    public function delete(CompanyService $service, int $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', Rule::exists('companies', 'id')],
        ]);
        if ($validator->fails()) {
            DialogAlert::warning($validator->errors()->get('id')[0]);
        } else {
            $owners_disassociated_total = $service->delete($id);
            Toast::success("$owners_disassociated_total donos desassociados");
        }
    }
}
