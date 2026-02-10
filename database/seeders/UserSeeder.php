<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => 'student User',
                'email' => 'student@example.com',
                'role' => 'student',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => 'instructor User',
                'email' => 'instructor@example.com',
                'role' => 'instructor',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole($userData['role']);
        }
    }
}
