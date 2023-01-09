<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Bramwel Ngayo',
            'email' => 'bngayo@cwsafrica.org',
            'username' => 'bngayo',
            'active' => true,
            'password' => Hash::make('123456789'),
        ]);
    }
}
