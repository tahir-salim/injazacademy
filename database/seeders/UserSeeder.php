<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $admin = [
            [
                'first_name' => 'Admin',
                'email' => 'admin@klabs.co',
                'password' => bcrypt('injaz@klabs123'),
                'role_id' => 1,
                "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()   # \Datetime()
            ]
        ];
        DB::table('users')->insert($admin);
    }
}
