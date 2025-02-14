<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = array(

            // array(
            //     'name' => 'Creative Writing',
            //     'display_name' => 'Creative Writing',
            //     'status' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ),
            // array(
            //     'name' => 'Graphic Design',
            //     'display_name' => 'Graphic Design',
            //     'status' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ),
            // array(
            //     'name' => 'Photography',
            //     'display_name' => 'Photography',
            //     'status' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ),
            // array(
            //     'name' => 'Economics',
            //     'display_name' => 'Economics',
            //     'status' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ),
            // array(
            //     'name' => 'Art',
            //     'display_name' => 'Art',
            //     'status' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ),
            array(
                'name' => 'Digital Literacy',
                'display_name' => 'Digital Literacy',
                'bg_color' => '#3e82db',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Work Readiness',
                'display_name' => 'Work Readiness',
                'bg_color' => '#33a6d5',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Financial Literacy',
                'display_name' => 'Financial Literacy',
                'bg_color' => '#fd7893',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Entrepreneurship',
                'display_name' => 'Entrepreneurship',
                'bg_color' => '#c29f20',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        );
        DB::table('categories')->insert($category);
    }
}
