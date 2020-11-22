<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i=0;$i<3;$i++) {
            DB::table('user')->insert([
                'username'=>$faker->unique()->userName,
                'password'=>Hash::make('admin123'),
            ]);
        }
    }
}
