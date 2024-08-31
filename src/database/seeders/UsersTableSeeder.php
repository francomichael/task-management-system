<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
         // Creating Admin User
         User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Assuming you have a 'role' column in users table
        ]);

        // Creating Regular User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user', // Assuming you have a 'role' column in users table
        ]);

        // Creating 5 additional normal users
        foreach (range(1, 5) as $index) {
            User::create([
                'name' => "Normal User $index",
                'email' => "user$index@example.com",
                'password' => Hash::make('password'),
                'role' => 'user', // Normal users
            ]);
        }
    }
}
