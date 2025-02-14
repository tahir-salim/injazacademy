<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tests = array(

            array(
                'title' => 'Landscape Photography',
                'sub_title' => 'Creative Writing',
                'body' => 'Creative Writing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'DSLR photography',
                'sub_title' => 'Graphic Design',
                'body' => 'Graphic Design',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'Great Graphic Design',
                'sub_title' => 'Photography',
                'body' => 'Photography',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'Make Your Voice Heard',
                'sub_title' => 'Creative Writing',
                'body' => 'Creative Writing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'The Vendors Toolkit',
                'sub_title' => 'Graphic Design',
                'body' => 'Graphic Design',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'The Vendors Kahns',
                'sub_title' => 'Photography',
                'body' => 'Photography',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'title' => 'The Shazam Khan',
                'sub_title' => 'Graphic Design',
                'body' => 'Graphic Design',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        );
        DB::table('tests')->insert($tests);
    }
}
