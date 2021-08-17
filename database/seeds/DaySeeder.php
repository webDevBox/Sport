<?php

use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = array(
            0 => array(
                'name'   => 'Monday'
            ),
            1 => array(
                'name'   => 'Tuesday'
            ),
            2 => array(
                'name'   => 'Wednesday'
            ),
            3 => array(
                'name'   => 'Thursday'
            ),
            4 => array(
                'name'   => 'Friday'
            ),
            5 => array(
                'name'   => 'Saturday'
            ),
            6 => array(
                'name'   => 'Sunday'
            ),
        );

        foreach($days as $day)
        {
            DB::table('days')->updateOrInsert([
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
                ],[
                    'name'   => $day['name']
            ]);
        }
    }
}
