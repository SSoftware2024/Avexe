<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PaymentMethodService
{
    public function getSrcPath(string $path = 'storage', bool $absolute_storage_path = true)
    {
        $storage_path = 'json/payment_methods_default.json';
        $storage_path = $absolute_storage_path ? Storage::path($storage_path) : $storage_path;
        return $path === 'storage' ? $storage_path : database_path('json/payment_methods_default.json');
    }
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
}
