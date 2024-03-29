<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create admin
        User::create([
            'id' => 1,
            'f_name' => 'Othman I',
            'l_name' => 'Ibrahim',
            'email' => 'othmaniissa2@gmail.com',
            'password' => Hash::make('password'),
        ]);

    }
}
