<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $test_questions = [
                "test_id" => rand(1, 10),
                "question" => 'Question ' . $i,
                "correct_answer" => 'Correct Answer ' . rand(1, 4),
                "order_number" => rand(1, 10),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('test_questions')->insert($test_questions);
        }
    }
}
