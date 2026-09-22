<?php

namespace App\Services;

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

    public function ownerCreateOrUpdate(array $data, ?int $id): User|int
    {
        return is_null($id) ?
            $this->ownerService->create($data) :
            $this->ownerService->update($data, $id);
    }

    public function ownerDelete(int $id)
    {
        return $this->ownerService->delete($id);
    }
    public function ownerGetDataPaginate(?int $paginate = 10, array $sort_by = [])
    {
        return $this->ownerService->getDataPaginate($paginate, $sort_by);
    }
    public function ownerToggleActive(int $id): int
    {
        return $this->ownerService->toggleActive($id);
    }

    public function companyCreateOrUpdate(array $data, ?array $owners_id = [])
    {
        empty($data['id']) ?
            $this->companyService->createByDeveloper($data, $owners_id) :
            $this->companyService->updateByDeveloper($data['id'], $data);
    }

    public function companyGetDataPaginate(?int $paginate = 10, array $sort_by = [])
    {
        return $this->companyService->getDataPaginateByDeveloper($paginate, $sort_by);
    }
}
