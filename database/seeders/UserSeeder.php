<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Scale;
use Illuminate\Database\Seeder;
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
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);
    }
}
