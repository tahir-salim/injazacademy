<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $content = [
                "title" => 'Title ' . $i,
                "type" => 'Type ' . $i,
                "url" =>  'www.' . rand(10, 20) . '.uk.co',
                "data" => 'Data ' . $i,
                "status" => "published " . $i,
                "duration" => rand(10, 20) . ":" . rand(10, 20) . ":" . rand(10, 20),
                "chapter_id" => rand(1, 9),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            DB::table('contents')->insert($content);
        }
    }
}
