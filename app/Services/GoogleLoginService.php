<?php

namespace App\Services;

use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

final class GoogleLoginService
{
     public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = null;
        if(!User::where('email', $googleUser->email)->exists()){
            $user = User::create([
                'email' => $googleUser->email,
                'email_verified_at' => now(),
                'name' => $googleUser->name,
                'profile' => $googleUser->avatar,
                'google_id' => $googleUser->id,
                'user_type' => TypeUser::CUSTOMER->value
            ]);
        }else{
            $user = User::where('email', $googleUser->email)->first();
        }
        Auth::login($user);
        return redirect('/home');
    }
}
