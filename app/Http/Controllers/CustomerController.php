<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Index');
    }
    public function appointmentsView()
    {
        return Inertia::render('user/AppointmentsCustomer', [
            'user' => Auth::user()
        ]);
    }
}
