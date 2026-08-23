<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index()
    {
        return Inertia::render('Dev/Index');
    }

    public function ownerListView()
    {
        return Inertia::render('Dev/OwnerList');
    }
}
