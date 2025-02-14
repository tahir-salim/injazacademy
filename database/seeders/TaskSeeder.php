<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $task = [
                "enrollment_id" => rand(1, 100),
                "file" => 'File '. $i,
                "views_count" => rand(0, 10),
                "is_reviewed" => rand(0, 1),
                "review_score" => rand(0, 10),
                "show_in_program" => rand(0, 1),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('tasks')->insert($task);
        }
    }
}
