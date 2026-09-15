<?php

namespace App\Services;

use App\Models\User;

final class DeveloperService
{
    private OwnerService $ownerService;

    public function __construct()
    {
        $this->ownerService = new OwnerService;
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
        return $this->ownerService->getData($paginate, $sort_by);
    }
}
