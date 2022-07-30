<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'full_name' => 'admin@admin1',
            'user_type' => 0,
            'password' => bcrypt('admin1234'),
        ]);
        User::create([
            'full_name' => 'admin2@admin',
            'user_type' => 1,
            'password' => bcrypt('admin@1234'),
        ]);
    }
}