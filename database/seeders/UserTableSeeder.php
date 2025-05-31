<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@gmail.com',
            'is_admin' => true,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name'     => 'General User',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name'     => 'General User 2',
            'email'    => 'user2@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
