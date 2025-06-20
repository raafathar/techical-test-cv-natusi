<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $admin = User::create([
            'name' => 'Admin',
            'phone' => '082332321868',
            'phonecode' => '62',
            'email' => 'apotek@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => null,
        ]);

        $admin->assignRole('admin');
    }
}