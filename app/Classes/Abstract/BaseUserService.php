<?php

namespace App\Classes\Abstract;

use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class BaseUserService
{
    protected TypeUser $userType;

    /**
     * create
     *
     * @param array $data
     *
     * @return User
     */
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
    /**
     * count
     *
     * @param string $column [coluna da tabela]
     * @param string $operator [operadores lógicos]
     * @param mixed $value [valor da coluna a contar]
     *
     * @return int
     */
    public function count(string $column, string $operator, mixed $value): int
    {
        return User::where('user_type', $this->userType->value)
            ->where($column, $operator, $value)->count();
    }
    /* ========================================================================== */
    /*                              STATIC PREFIX 'S'                             */
    /* ========================================================================== */

    public static function s_update(array $data, int $id): int
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

    public static function s_delete(int $id): int
    {
        //futuramente deletar suas relações primeiro, caso use forceDelete
        $deleted = User::where('id', $id)->forceDelete();
        return $deleted;
    }

    public static function s_toggleActive(int $id): int
    {
        $user = User::find($id);
        $user->active = !$user->active;
        return $user->save();
    }

    public static function s_getUserData(int $id): ?User
    {
        $user = User::find($id);
        return $user;
    }

    /* ========================================================================== */
    /*                                    VIEW                                    */
    /* ========================================================================== */    
    /**
     *  getListViewData
     *
     * @param ?int $paginate
     * @param array $sort_by
     * @param array $filter [name_email, active]
     *
     * @return mixed UserPaginate<App\Models\User>
     */
    public function getListViewData(?int $paginate = 10, array $sort_by = [], array $filter = []): mixed
    {
        $users = User::where('id', '!=', auth()->id())->where('user_type', $this->userType->value);
        isset($filter['active']) ? $users->where('active', filter_var($filter['active'], FILTER_VALIDATE_BOOLEAN)  ? 1 : 0) : null;
        if (!empty($filter['name_email'])) {
            $users->where(function ($query) use ($filter) {
                $query->where('name', 'like', "%{$filter['name_email']}%")->orWhere('email', 'like', "%{$filter['name_email']}%");
            });
        }
        if (!empty($sort_by)) { //vuetify datatable
            $users->orderBy($sort_by[0]['key'], $sort_by[1]['order']);
        }
        $users = $users->paginate($paginate);
        return $users;
    }
}
