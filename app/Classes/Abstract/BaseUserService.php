<?php

namespace App\Classes\Abstract;

use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class BaseUserService
{
    protected TypeUser $userType;
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'whatsapp' => $data['whatsapp'],
            'user_type' => $this->userType->value,
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
        if (!empty($data['password'])) {
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
        $deleted = User::where('id', $id)->forceDelete();
        return $deleted;
    }
    public function toggleActive(int $id): int
    {
        $user = User::find($id);
        $user->active = !$user->active;
        return $user->save();
    }

    public function getUserData(int $id): ?User //dado
    {
        $user = User::find($id);
        return $user;
    }


    # ================================================================================= #
    #                                    View
    # ================================================================================= #
    public function getListViewData(?int $paginate = 10, array $sort_by = [], array $filter = [])
    {
        $users = User::where('id', '!=', auth()->id())->where('user_type', $this->userType->value);
        if (!empty($filter['name_email'])) {
            $users->where(function ($query) use ($filter) {
                $query->where('name','like',"%{$filter['name_email']}%")->orWhere('email', 'like', "%{$filter['name_email']}%");
            });
        }
        if (!empty($sort_by)) { //vuetify datatable
            $users->orderBy($sort_by[0]['key'], $sort_by[1]['order']);
        }
        $users = $users->paginate($paginate);
        return $users;
    }
}
