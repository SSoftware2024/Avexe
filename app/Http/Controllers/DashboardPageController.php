<?php

namespace App\Http\Controllers;

use App\Enum\TypeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardPageController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->user_type;
        switch ($user_type) {
            case TypeUser::CUSTOMER->value:
                return redirect()->route('customer.profileView');
                break;

            default:
                # code...
                break;
        }

    }
}
