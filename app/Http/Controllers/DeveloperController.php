<?php

namespace App\Http\Controllers;

use App\Facades\DialogAlert;
use App\Facades\Toast;
use App\Models\Company;
use App\Models\User;
use App\Services\CompanyService;
use App\Services\DeveloperService;
use App\Services\OwnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index()
    {
        return Inertia::render('Dev/Index');
    }

}
