<?php

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
        \App\User::create(['name' => "Abdulmalik", 'email' => "alsufiran@gmail.com", 'password' => bcrypt('alive2tinker')]);
    }
}
