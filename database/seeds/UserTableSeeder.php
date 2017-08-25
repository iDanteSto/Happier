<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
      {
        DB::table('users')->insert([
            'nickname' => ('Fernanda'),
            'email' => ('fernanda@ciqno.com'),
            'password' => Hash::make('hotcake'),
           // 'created_at' => timestamps(),
            //'updated_at' => timestamps(),
        ]);
    }
}

