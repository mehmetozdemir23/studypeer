<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'firstname' => 'mehmet',
            'lastname' => 'ozdemir',
            'email' => 'admin@studypeer.com',
            'password' => bcrypt('adminpassword'),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);
    }
}
