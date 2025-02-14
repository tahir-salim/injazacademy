<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $like = [
                "likeable_id" => rand(1, 100),
                "likeable_type" => '\App\Models\Task',
                "is_like" => rand(0, 1),
                "user_id" => rand(1, 5),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('likes')->insert($like);
        }
        
    }
}
