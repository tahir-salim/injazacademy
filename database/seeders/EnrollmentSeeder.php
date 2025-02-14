<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $enrollment = [
                "user_id" => rand(1, 10),
                "program_id" => rand(1, 18),
                "status" => "Done " . $i,
                "transaction_id" => rand(100, 9999),
                "started_date" => Carbon::now()->subDays($i),
                "finished_date" => Carbon::now()->addDays($i),
                "test_score" => rand(1, 10),
                "certificate_url" => 'https:/' . rand(10, 200) . '/uk.com',
                "is_certified" => rand(0, 1),
                "certification_date" => Carbon::now()->addDays(100),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('enrollments')->insert($enrollment);
        }
    }
}
