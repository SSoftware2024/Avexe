<?php

namespace App\Services;

use App\Enum\TypeUser;
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

    public function updateCompany(int $company_id, array $data, array $old_owners_id): int
    {

        $total_rows = Company::where('id', $company_id)->update([
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'name' => $data['name'],
            'corporate_name' => $data['corporate_name'],
            'tag_url' => $data['tag_url'],
        ]);
        $owners_id = $data['owners_ids'];
        $new_owners_id = array_values(array_diff($owners_id, $old_owners_id));
        $remove_owners_id = array_values(array_diff($old_owners_id, $owners_id));
        if (!empty($remove_owners_id)) {
            $total_rows += $this->companyService->removeOwners($company_id, $remove_owners_id);
        }
        if (!empty($new_owners_id)) {
            $total_rows += $this->companyService->associateOwners($new_owners_id, $company_id);
        }
        return $total_rows;
    }


    # ================================================================================= #
    #                                    PRIVADOS
    # ================================================================================= #


}
