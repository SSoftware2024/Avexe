<?php

namespace Database\Seeders;

use App\Enum\TypeUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //user raiz
        if (true) {
            User::create([
                'name' => 'Tiago Alves',
                'whatsapp' => '88994135616',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'user_type' => TypeUser::DEVELOPER->value,
                'date_of_birth' => date('Y-m-d', strtotime('2001-02-02')),
                'password' => Hash::make('ssoftware'),
            ]);
        }
        $fakes = false;
        if ($fakes) {
            $this->call(UsersTableSeeder::class);
        }


        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
