<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1,20) as $index)
        {
            DB::table('venues')->insert([
                'name'    => $faker->sentence(5),
                'address' => $faker->paragraph(4),
                'lat'     => Str::random(2).Str::random(5),
                'lng'     => Str::random(2).Str::random(5)
            ]);
        }

    }
}
