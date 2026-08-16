<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

trait HasProfilePhoto
{
    protected string $profile_field = 'profile';

    protected string $profile_path = 'user_profile';

    public function uploadProfile(UploadedFile $file)
    {
        if ($file instanceof UploadedFile && $file->isValid()) {
            $this->removeProfile();
            $filename = 'profile_'.bin2hex(random_bytes(16)).'.'.$file->extension();
            $image = Image::decode($file)->resize(80, 80);
            Storage::disk('public')->put(
                "{$this->profile_path}/$filename",
                $image->encode(),
            );
            $this->{$this->profile_field} = $filename;
        } else {
            throw new \Exception("Invalid upload file. Expected UploadedFile, but received: '".get_debug_type($file)."'");
        }
    }

    /**
     * @method profileUrl()
     *
     * Accessor|Mutator do Eloquent.
     */
    protected function profileUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->profile) {
                    return null;
                }
                // Se já é uma URL completa (ex: foto do Google), retorna direto
                if (str_starts_with($this->profile, 'http://') || str_starts_with($this->profile, 'https://')) {
                    return $this->profile;
                }

                return asset("storage/{$this->profile_path}/".$this->profile);
            }
        );
    }

    public function removeProfile()
    {
        $filename = $this->profile_path.'/'.$this->{$this->profile_field};
        if (Storage::disk('public')->exists($filename)) {
            Storage::disk('public')->delete($filename);
        }
        $this->{$this->profile_field} = null;
    }
}
