<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin account
        User::updateOrCreate(
            ['email' => 'admin@checkapp.local'],
            [
                'name'       => 'Administrator',
                'username'   => 'admin',
                'email'      => 'admin@checkapp.local',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'gender'     => 'male',
                'is_active'  => true,
            ]
        );

        // Demo user
        User::updateOrCreate(
            ['email' => 'user@checkapp.local'],
            [
                'name'        => 'Demo User',
                'username'    => 'demouser',
                'email'       => 'user@checkapp.local',
                'password'    => Hash::make('user123'),
                'role'        => 'user',
                'gender'      => 'male',
                'department'  => 'Engineering',
                'employee_id' => 'EMP001',
                'birth_date'  => '1990-01-15',
                'is_active'   => true,
            ]
        );
    }
}
