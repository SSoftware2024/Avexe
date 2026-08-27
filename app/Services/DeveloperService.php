<?php

namespace App\Services;

use App\Models\User;

final class DeveloperService
{
    private OwnerService $ownerService;
    public function __construct()
    {
        $this->ownerService = new OwnerService();
    }
    public function ownerCreateOrUpdate(array $data, ?int $id): User|int
    {
        return is_null($id) ?
            $this->ownerService->create($data) :
            $this->ownerService->update($data, $id);
    }
}
