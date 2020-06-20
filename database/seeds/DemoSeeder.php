<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //add the demo user
        DB::table('users')->insert([
            'name' => 'Molly Green',
            'email' => 'molly@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
