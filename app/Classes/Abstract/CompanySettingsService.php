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
        return $this->getModel()::where($this->column_id, $this->company_id)->count() == 7;
    }
    public function get()
    {
        return $this->getModel()::where($this->column_id, $this->company_id)->firstOrFail();
    }
    public function delete()
    {
        $this->getModel()::where($this->column_id, $this->company_id)->forceDelete();
    }
    private function create(array $data)
    {
        $data[$this->column_id] = $this->company_id;
        $this->getModel()::create($data);
    }
    private function update(array $data)
    {
        $model = $this->getModel()::query();
        $model->where($this->column_id, $this->company_id);
        if(array_key_exists('day_of_week', $data)){
            $model->where('day_of_week', $data['day_of_week']);
        }
        $model->update($data);
    }
}
