<?php

namespace App\Services;

use App\Enum\AccountType;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodService
{
    private int $company_id = 0;

    public function setCompanyId(int $company_id)
    {
        $this->company_id = $company_id;
        return $this;
    }
    public function getCompanyId()
    {
        return $this->company_id;
    }
    public function create(array $data)
    {
        return PaymentMethod::create([
            'name' => strtoupper($data['name']),
            'account_type' => $data['account_type'],
            'company_id' => $this->getCompanyId()
        ]);
    }
    public function delete(int $id): int
    {
        return PaymentMethod::where('id', $id)->delete();
    }
    #region METÓDOS JSON
    public function getJsonFilePath(string $path = 'storage', bool $decoded = false): mixed
    {
        if ($this->exists($path)) {
            $json = file_get_contents($this->getSrcPath($path));
            return $decoded ? json_decode($json) : $json;
        }
        return false;
    }
    public function createJson(string|object $json_content)
    {
        if (!$this->exists()) { //caso não exista copia json database
            $json_content = $this->exists('database') ?
                file_get_contents($this->getSrcPath(false)) :
                json_encode(['aviso' => 'Arquivo json database não existia, criado json storage de maneira não padronizada!']);
        }
        Storage::put($this->getSrcPath(absolute_storage_path: false), $json_content);
    }
    public function exists(string $path = 'storage')
    {
        return $path === 'storage' ? Storage::exists($this->getSrcPath(absolute_storage_path: false)) : File::exists($this->getSrcPath('database'));
    }
    public function isValidJson(): bool
    {
        $is_valid = false;
        $json_data = $this->getJsonFilePath();
        if ($json_data != false) {
            $validator = Validator::make(['json_content' => $json_data], [
                'json_content.*' => ['required', 'array:name,account_type'],
                'json_content.*.account_type' => [
                    'required',
                    'string',
                    Rule::in(AccountType::toArrayValues()),
                ],
                'json_content.*.name' => [
                    'required',
                    'string',
                    'regex:/^[A-ZÀ-ÖØ-Þ\s]+$/u'
                ],
            ]);
            $is_valid = $validator->passes();
        }
        return $is_valid;
    }
    #endregion FIM METÓDOS JSON
    /* ========================================================================== */
    /*                                  PRIVADOS                                  */
    /* ========================================================================== */
    private function getSrcPath(string $path = 'storage', bool $absolute_storage_path = true)
    {
        $storage_path = 'json/payment_methods_default.json';
        $storage_path = $absolute_storage_path ? Storage::path($storage_path) : $storage_path;
        return $path === 'storage' ? $storage_path : database_path('json/payment_methods_default.json');
    }
}
