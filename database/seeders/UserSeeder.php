<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            [
                'username'   => 'admin',
                'password'   => Hash::make('123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'staff',
                'password'   => Hash::make('123'),
                'role'       => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
