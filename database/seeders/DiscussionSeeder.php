<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $discussion = [
                "enrollment_id" => rand(1, 10),
                "body" => 'Body ' . $i,
                "reply_id" => rand(1, 10),
                "user_id" => rand(1, 10),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('discussions')->insert($discussion);
        }
    }
}
