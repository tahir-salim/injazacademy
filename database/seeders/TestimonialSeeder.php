<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $testimonials = [
                "enrollment_id" => rand(1, 100),
                "body" => 'Body ' . $i,
                "is_approved" => rand(0, 1),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('testimonials')->insert($testimonials);
        }
    }
}
