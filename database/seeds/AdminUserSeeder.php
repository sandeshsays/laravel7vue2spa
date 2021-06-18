<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'superadmin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'),
            'type' => 'admin',
        ]);
    }
}
