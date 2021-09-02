<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            ['name' => 'Diego Garcia', 'email' => 'diego@gmail.com', 'password' => Hash::make('diego'),'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Jennifer Pich', 'email' => 'jenni@gmail.com', 'password' => Hash::make('jenni'),'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Albert Aroca', 'email' => 'albert@gmail.com', 'password' => Hash::make('albert'),'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );

        DB::table('users')->insert($users);
    }
}
