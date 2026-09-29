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




    public function updateCompany(int $company_id, array $datas)
    {

        //verficar se o ids enviados possuem os old ids
        // sim: corta os olds e fica com os novos, atuliza valores novos
        //não atuliza para null ids antes vinculados e atualiza valores novos
        //old ids não podem vir null caso nenhum id novo tenha sido marcado
        
        //desassociar antigos
        //associar novos
        //atualizar
    }


    # ================================================================================= #
    #                                    PRIVADOS
    # ================================================================================= #


}
