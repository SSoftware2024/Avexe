<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySystemSetting extends Model
{
    use SoftDeletes;
    protected $guarded = [];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
