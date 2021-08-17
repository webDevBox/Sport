<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
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
        // $faker = Faker::create();
        // foreach(range(1,20) as $index)
        // {
        //     DB::table('users')->insert([
        //         'first_name' => $faker->name(),
        //         'last_name'  => $faker->name(),
        //         'email'      => $faker->unique()->email,
        //         'password'   => bcrypt(123456),
        //         'phone'      => '+92'.rand(1000000,9999999),
        //         'role_id'    => 2,
        //         'device_token' => Str::random(2)

        //     ]);
        // }

        $role = DB::table('roles')->where('name','admin')->first();
        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name'  => 'cybexo',
            'email'      => 'admin@cybexo.com',
            'password'   => bcrypt(123456),
            'phone'      => '+92'.rand(1000000,9999999),
            'role_id'    => $role->id,
            'device_token' => Str::random(25)

        ]);
    }
}
