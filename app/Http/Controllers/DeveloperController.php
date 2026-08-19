<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index()
    {
        return Inertia::render('Dev/Index');
    }
    public function profileView()
    {
        return Inertia::render('Auth/Profile');
    }
    public function removeProfile()
    {
        // $customer_service->removeProfile(Auth::user());
    }
}