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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'whatsapp' => $data['whatsapp'],
            'user_type' => TypeUser::OWNER->value,
            'date_of_birth' => date('Y-m-d', strtotime($data['date_of_birth'])),
            'password' => Hash::make($data['password']),
        ]);

        //futuramente colocar envio de email aq
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
        if (! empty($data['password'])) {
            $values = [
                ...$values,
                'password' => Hash::make($data['password']),
            ];
        }
        $updated = User::where('id', $id)->update($values);

        return $updated;
    }
    public function delete(int $id): int
    {
        //futuramente deletar suas relações primeiro, caso use forceDelete
        $deleted = User::where('id', $id)->where('user_type', TypeUser::OWNER->value)->forceDelete();
        return $deleted;
    }

    public function getData(?int $paginate = 10, array $sort_by = [])
    {
        $owners = User::where('user_type', TypeUser::OWNER->value);
        
        if(!empty($sort_by)){
            $owners->orderBy($sort_by[0]['key'],$sort_by[1]['order']);
        }
        $owners = $owners->paginate($paginate);
        return $owners;
    }
}
