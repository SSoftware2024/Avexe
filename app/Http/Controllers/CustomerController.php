<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\GoogleLoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Index');
    }
    public function appointmentsView()
    {
        return Inertia::render('User/AppointmentsCustomer');
    }
    public function profileView()
    {
        return Inertia::render('User/Profile');
    }
    public function removeProfile(CustomerService $customer_service)
    {
        $customer_service->removeProfile(Auth::user());
    }

    public function loginGoogle(GoogleLoginService $googleService)
    {
        return $googleService->redirect();
    }
    public function loginGoogleCallback(GoogleLoginService $googleService)
    {
        return $googleService->callback();
    }
}
