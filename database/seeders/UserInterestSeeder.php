<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $user_interests = [
                "user_id" => rand(1, 5),
                "category_id" => rand(1, 12),
                "tag_id" => rand(1, 30),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('user_interests')->insert($user_interests);
        }
    }
}
