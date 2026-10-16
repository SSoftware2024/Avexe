<?php

namespace App\Http\Controllers;

use App\Enum\TypeUser;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->user_type;
        switch ($user_type) {
            case TypeUser::CUSTOMER->value:
                return redirect()->route('auth.profileView');
                break;
            case TypeUser::DEVELOPER->value:
                return redirect()->route('developer');
                break;
            case TypeUser::OWNER->value:
                return redirect()->route('owner');
                break;

            default:
                # code...
                break;
        }
    }
    public function profileView()
    {
        return Inertia::render('Auth/Profile');
    }
    public function removeProfile(AuthService $auth_service)
    {
        $auth_service->removeProfile(Auth::user());
    }
    public function confirmPasswordInertiaXHR(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);
        if (!Hash::check($request->password, $request->user()->password)) {
            $errors = new MessageBag();
            $errors->add('password', 'A senha fornecida está incorreta.');
            return redirect()->back()->withErrors($errors);
        }
        $request->session()->passwordConfirmed();
    }
}
