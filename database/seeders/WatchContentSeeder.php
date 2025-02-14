<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WatchContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $watch_contents = [
                "user_id" => rand(1, 5),
                "content_id" => rand(1, 100),
                "time_spent" => rand(5, 60),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('watch_contents')->insert($watch_contents);
        }
    }
}
