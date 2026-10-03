<?php

namespace App\Services;

use App\Models\Company;

final class CompanyOperatingHoursService
{
    private int|Company $company;
    public function __construct(int|Company $company)
    {
        $this->company = $company;
    }
    public function save() {}
    public function exists() {}
    public function get() {}
    private function create() {}
    private function update() {}
}
