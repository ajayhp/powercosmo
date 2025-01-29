<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB,Hash;
use App\Models\User;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $users = array(
            array(
                'name' => 'Admin User',
                'email' => 'admin@powercosmo.com',
                'password' => Hash::make(12345678),
                'is_admin'=>User::ADMIN
            ),
            array(
                'name' => 'Employee Powercosmo',
                'email' => 'ajay@powercosmo.com',
                'password' => Hash::make(12345678),
                'is_admin'=>User::CUSTOMER

            ),
            array(
                'name' => 'Employee User',
                'email' => 'test@gmail.com',
                'password' => Hash::make(12345678),
                'is_admin'=>User::CUSTOMER

            )
        );

        DB::table('users')->insert($users);

    }
}
