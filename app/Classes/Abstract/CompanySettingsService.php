<?php

namespace App\Classes\Abstract;

abstract class CompanySettingsService
{
    private int $company_id = 0;
    private string $column_id = 'company_id';
    public function __construct(int $company_id)
    {
        $this->company_id = $company_id;
    }
    protected abstract function getModel();

    public function getCompanyId()
    {
        return $this->company_id;
    }
    public function save(array $data)
    {
        $this->exists() ? $this->update($data) : $this->create($data);
    }
    public function exists()
    {
        return $this->getModel()->where($this->column_id, $this->company_id)->exists();
    }
    public function get()
    {
        return $this->getModel()->where($this->column_id, $this->company_id)->firstOrFail();
    }
    public function delete()
    {
        $this->getModel()->where($this->column_id, $this->company_id)->forceDelete();
    }
    private function create(array $data)
    {
        $data[$this->column_id] = $this->company_id;
        $this->getModel()->create($data);
    }
    private function update(array $data)
    {
        $this->getModel()->where($this->column_id, $this->company_id)->update($data);
    }
}
