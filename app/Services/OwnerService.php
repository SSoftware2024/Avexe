<?php

namespace App\Services;

use App\Enum\TypeUser;
use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OwnerService
{
    public function create(array $data): User
    {
        // if(Auth::user()->user_type != TypeUser::DEVELOPER->value){
        //     throw new \Exception("Owner not be created by other user not developer");  
        // }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'whatsapp' => $data['whatsapp'],
            'date_of_birth' => date('Y-m-d', strtotime($data['date_of_birth'])),
            'password' => Hash::make($data['password'])
        ]);
        return $user;
    }
    public function update(array $data, int $id): int
    {
        $values = [
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'whatsapp' => $data['whatsapp'],
            'date_of_birth' => date('Y-m-d', strtotime($data['date_of_birth'])),
        ];
        if(!empty($data['password'])){
            $values = [
                ...$values,
                'password' => Hash::make($data['password'])
            ];
        }
        $updated = User::where('id', $id)->update($values);
        return $updated;
    }


}
