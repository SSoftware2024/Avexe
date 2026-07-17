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
        // User::factory(10)->create();

        User::create([
            'name' => 'Tiago Alves',
            'whatsapp' => '88 994135616',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'user_type' => TypeUser::DEVELOPER->value,
            'is_register_completed' => true,
            'date_of_birth' => date('Y-m-d', strtotime('2001-02-02')),
            'password' => Hash::make('ssoftware'),
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
