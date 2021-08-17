<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = array(
            0 => array(
                'name'   => 'Cricket'
            ),
            1 => array(
                'name'   => 'Football'
            ),
            2 => array(
                'name'   => 'T20 Cricket'
            )
        );

        foreach($games as $game)
        {
            DB::table('games')->updateOrInsert([
                    'name'   => $game['name'],
                    'status' => 1
                ],[
                    'name'   => $game['name'],
                    'status' => 1
            ]);
        }

    }
}
