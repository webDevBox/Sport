<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            VenueSeeder::class,
            UserSeeder::class,
            GameSeeder::class,
            DaySeeder::class,
            TeamSeeder::class
        ]);
        // $this->call(UserSeeder::class);
    }
}
