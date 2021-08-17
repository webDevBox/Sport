<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Game;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('otp', null)->first();
        $game = Game::where('status',1)->first();
      
        DB::table('teams')->updateOrInsert([
            'captain_id'        => $user->id,
            'name'              => 'Black Pool',
            'num_of_players'    => 1,
            'game_id'           => $game->id,
            'availability'      => 1,
            'distance'          => '5 KM'
        ]);
    }
}
