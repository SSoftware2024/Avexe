<?php

namespace App\Http\Controllers;

use App\Enum\TypeUser;
use App\Models\User;
use App\Services\DeveloperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index()
    {
        return Inertia::render('Dev/Index');
    }

    public function ownerCreateUpdateView(Request $request, ?User $user)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            return redirect()->back();
        }
        return Inertia::render('Dev/OwnerCreateUpdate', [
            'user_data' => $user->exists ? $user : null
        ]);
    }
    public function ownerListView()
    {
        return Inertia::render('Dev/OwnerList');
    }

    public function ownerCreateOrUpdate(Request $request, DeveloperService $service)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            //usuario não pode fazer ação
            return;
        }
        $id = $request->id;
        $request->validate([
            'id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                !is_null($id) ? Rule::unique('users')->ignore($id) : Rule::unique(User::class),
            ],
            'password' => [is_null($id) ? 'required' : 'nullable', 'string', Password::min(8)],
            'whatsapp' => [
                'required',
                'digits:11',
                !is_null($id) ? Rule::unique('users')->ignore($id) : Rule::unique(User::class),
            ],
        ]);
        $data = $request->all();
        $service->ownerCreateOrUpdate($data, $id);
    }
}
