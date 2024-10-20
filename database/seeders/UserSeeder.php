<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],  
            [
                'name' => 'Test User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123456'), 
            ]
        );
    }
}
