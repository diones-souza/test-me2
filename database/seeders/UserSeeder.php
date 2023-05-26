<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Scale;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userFactory = new UserFactory();
        $role = Role::factory()->create([
            'name' => 'Administrator',
        ]);
        $scale = Scale::factory()->create([
            'name' => 'Standard scale - 08:00 to 17:00',
        ]);
        User::factory()->create([
            'name' => 'Administrator',
            'nickname' => 'admin',
            'email' => 'admin@noemail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'cpf' => $userFactory->getRandomCpf(),
            'register' => Str::random(5),
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);
    }
}
