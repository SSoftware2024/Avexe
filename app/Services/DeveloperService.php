<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;

final class DeveloperService
{
    private OwnerService $ownerService;
    private CompanyService $companyService;

    public function __construct()
    {
        $this->ownerService = new OwnerService();
        $this->companyService = new CompanyService();
    }


    public function createCompany(array $data, array $owners_id): Company
    {
        $company = Company::create([
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'name' => $data['name'],
            'corporate_name' => $data['corporate_name'],
            'tag_url' => $data['tag_url'],
        ]);
        $this->associateOwners($owners_id, $company->id);
        return $company;
    }

    public function updateCompany(int $company_id, array $datas)
    {
        //desassociar antigos
        //associar novos
        //atualizar
    }


    # ================================================================================= #
    #                                    PRIVADOS
    # ================================================================================= #

    private function associateOwners(array $owners_id, int $company_id): int
    {
        return User::whereIn('id', $owners_id)->update([
            'company_id' => $company_id
        ]);
    }
}
