<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $follower = [
                "user_id" => rand(1, 9),
                "follower_id" => rand(1, 9),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('followers')->insert($follower);
        }
    }
}
