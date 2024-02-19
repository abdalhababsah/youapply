<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        DB::table('users')->insert([

            'name'     => 'email',
            'email'    => 'email@gmail.com',
            'password' => Hash::make('password'),
            'phone_number'=>'0782445888',
        ]);
    }
}
