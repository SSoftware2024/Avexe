<?php

namespace App\Classes;

use App\Models\User;

final class OwnerManager
{
    private int $owner_id;
    public function __construct(?int $id = null)
    {
        !empty($id) ? $this->setId($id) : null;
    }

    public function setId(int $id)
    {
        $this->owner_id = $id;
    }
    public function getId()
    {
        return $this->owner_id;
    }
    public function associateCompany(array $ids,int $company_id)
    {
        User::whereIn('id', $ids)->update([
            'company_id' => $company_id
        ]);
    }
}
