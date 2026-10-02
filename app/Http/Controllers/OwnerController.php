<?php

namespace App\Http\Controllers;

use App\Facades\DialogAlert;
use App\Facades\Toast;
use App\Models\User;
use App\Services\OwnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class OwnerController extends Controller
{
    public function index()
    {
        return Inertia::render('Owner/Index');
    }
    public function profileView()
    {
        return Inertia::render('Auth/Profile');
    }
    public function removeProfile()
    {
        // $customer_service->removeProfile(Auth::user());
    }



    public function listView(Request $request, OwnerService $service)
    {
        $request->validate([
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
            'sort_by' => ['nullable', 'array']
        ]);
        $per_page = $request->has('per_page') ? $request->per_page : 10;
        $sort_by = $request->has('sort_by') ? $request->sort_by : [];
        $owners = $service->ownerListViewData($per_page, $sort_by);
        return Inertia::render('Dev/Owner/List', [
            'owners' => $owners
        ]);
    }

    public function createUpdateView(Request $request, ?User $user)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->route('auth.profileView');
        }

        return Inertia::render('Dev/Owner/CreateUpdate', [
            'user_data' => $user->exists ? $user : null,
        ]);
    }

    public function createOrUpdate(Request $request, OwnerService $service)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
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
        is_null($id) ?
            $service->create($data) :
            $service->update($data, $id);
        Toast::success('Operação realizada com sucesso!');
    }

    public function delete(OwnerService $service, int $id)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
        }
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            DialogAlert::warning($validator->errors()->get('id')[0]);
        } else {
            $service->delete($id);
            Toast::success('Operação realizada com sucesso!');
        }
    }
    public function toggleActive(OwnerService $service, int $id)
    {
        if (Auth::user()->cannot('ownerManager', User::class)) {
            DialogAlert::error('Usuário sem permição para ação desejada.');
            return redirect()->back();
        }
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            DialogAlert::warning($validator->errors()->get('id')[0]);
        } else {
            $service->toggleActive($id);
            Toast::success('Operação realizada com sucesso!');
        }
    }
}
