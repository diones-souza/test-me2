<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * @return string
     */
    function getRandomCpf()
    {
        $number = '';
        for ($i = 0; $i < 11; $i++) {
            $number .= rand(0, 9);
        }
        return $number;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Administrator',
            'nickname' => 'admin',
            'email' => 'admin@noemail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'cpf' => $this->getRandomCpf(),
            'register' => Str::random(5),
        ]);
    }
}
