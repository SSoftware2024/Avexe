<?php

namespace App\Http\Controllers;

use App\Facades\DialogAlert;
use App\Facades\Toast;
use App\Models\Company;
use App\Models\User;
use App\Services\DeveloperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index()
    {
        return Inertia::render('Dev/Index');
    }

    # ================================================================================= #
    #                                    Empresa Telas
    # ================================================================================= #
    public function companyListView(Request $request, DeveloperService $service)
    {

        return Inertia::render('Dev/Company/List');
    }
    public function companyCreateOrUpdateView(Request $request, DeveloperService $service)
    {
        $owners = User::where('user_type', 'owner')
            ->whereNull('company_id')->get(['id', 'name', 'user_type']);

        return Inertia::render('Dev/Company/CreateUpdate', [
            'owners' => $owners
        ]);
    }

    # ================================================================================= #
    #                                    Owner Telas
    # ================================================================================= #


    public function ownerListView(Request $request, DeveloperService $service)
    {
        $request->validate([
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
            'sort_by' => ['nullable', 'array']
        ]);
        $per_page = $request->has('per_page') ? $request->per_page : 10;
        $sort_by = $request->has('sort_by') ? $request->sort_by : [];
        $owners = $service->ownerGetDataPaginate($per_page, $sort_by);
        return Inertia::render('Dev/Owner/List', [
            'owners' => $owners
        ]);
    }

    public function ownerCreateUpdateView(Request $request, ?User $user)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->route('auth.profileView');
        }

        return Inertia::render('Dev/Owner/CreateUpdate', [
            'user_data' => $user->exists ? $user : null,
        ]);
    }

    # ================================================================================= #
    #                                    Owner Métodos
    # ================================================================================= #

    public function ownerCreateOrUpdate(Request $request, DeveloperService $service)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
        }
        $id = $request->id;
        $request->validate([
            'id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                !is_null($id) ? Rule::unique('users')->ignore($id) : Rule::unique(User::class),
            ],
            'password' => [is_null($id) ? 'required' : 'nullable', 'string', Password::min(8)],
            'whatsapp' => [
                'required',
                'digits:11',
                !is_null($id) ? Rule::unique('users')->ignore($id) : Rule::unique(User::class),
            ],
        ]);
        $data = $request->all();
        $service->ownerCreateOrUpdate($data, $id);
        Toast::success('Operação realizada com sucesso!');
    }

    public function ownerDelete(DeveloperService $service, int $id)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
        }
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            DialogAlert::warning($validator->errors()->get('id')[0]);
        } else {
            $service->ownerDelete($id);
            Toast::success('Operação realizada com sucesso!');
        }
    }
    public function ownerToggleActive(DeveloperService $service, int $id)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
        }
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            DialogAlert::warning($validator->errors()->get('id')[0]);
        } else {
            $service->ownerToggleActive($id);
            Toast::success('Operação realizada com sucesso!');
        }
    }

    # ================================================================================= #
    #                                    Company Métodos
    # ================================================================================= #

    public function companyCreateOrUpdate(Request $request, DeveloperService $service)
    {
        $cpf_or_cnpj_is_required = (empty($request->cpf) && empty($request->cnpj)) ? 'required' : 'nullable';
        $id = $request->id;
        $request->validate([
            'id' => ['nullable', 'integer', Rule::exists('company', 'id')],
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
            ]
        ], [
            'cnpj.required' => 'Informe CNPJ ou CPF',
            'cpf.required' => 'Informe CNPJ ou CPF',
            'tag_url.regex' => 'Sem espaço, apenas letras, números e _'
        ]);
        $data = $request->all();
        $service->companyCreateOrUpdate($data, $data['owners_ids']);
        Toast::success('Operação realizada com sucesso!');
    }
}
