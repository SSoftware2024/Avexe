<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordBasicRules(),
            'whatsapp' => [
                'required',
                'digits:11',
                Rule::unique(User::class)
            ],
            'terms_of_use' => ['accepted']
        ], [], [
            'terms_of_use' => 'termos de uso'
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'whatsapp' => $input['whatsapp'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        // $user->sendEmailVerificationNotification();
        return $user;
    }
}
