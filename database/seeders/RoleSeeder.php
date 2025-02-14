<?php

namespace Database\Seeders;

use Carbon\Carbon;
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

            array(
                'name' => 'Admin',
                'display_name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Mentor',
                'display_name' => 'Mentor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Student',
                'display_name' => 'Student',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        );
        DB::table('roles')->insert($roles);
    }
}
