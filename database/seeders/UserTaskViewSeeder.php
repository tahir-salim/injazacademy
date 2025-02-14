<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTaskViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $user_task_views = [
                "user_id" => rand(1, 5),
                "task_id" => rand(1, 100),
                "datetime" => Carbon::now()->subDays(rand(1,50)),
                "is_view" => rand(0, 1),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('user_task_views')->insert($user_task_views);
        }
    }
}
