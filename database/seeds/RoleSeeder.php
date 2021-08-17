<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            0 => array(
                'name' => 'admin',
            ),
            1 => array(
                'name' => 'user',
            )
        );

        foreach ($roles as $role){
            DB::table('roles')->updateOrInsert([
                'name' => $role['name']
            ],[
                'name' => $role['name']
            ]);
        }
    }
}
