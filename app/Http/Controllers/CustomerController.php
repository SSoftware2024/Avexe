<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Index');
    }
    public function appointmentsView()
    {
        return Inertia::render('user/AppointmentsCustomer');
    }
}
