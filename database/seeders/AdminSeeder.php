<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB,Hash;
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
                'is_admin'=>1
            ),
            array(
                'name' => 'Employee User',
                'email' => 'ajay@powercosmo.com',
                'password' => Hash::make(12345678), 
                'is_admin'=>0

            )
        );

        DB::table('users')->insert($users);

    }
}
