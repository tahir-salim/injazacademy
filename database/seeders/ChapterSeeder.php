<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            $chapters = array(

                array(
                    'title' => 'Understanding Your DSLR',
                    'sub_title' => 'Understanding Your DSLR',
                    'body' => 'Understanding Your DSLR',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 1,
                    'language' => 'english',
                ),
                array(
                    'title' => 'Studio Demonstration',
                    'sub_title' => 'Studio Demonstration',
                    'body' => 'Studio Demonstration',
                    'order_number' =>2,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 1,
                    'language' => 'english',
                ),
                array(
                    'title' => 'Finding Beauty Everywhere',
                    'sub_title' => 'Finding Beauty Everywhere',
                    'body' => 'Finding Beauty Everywhere',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 2,
                    'language' => 'arabic',
                ),
                array(
                    'title' => 'Equipment and Settings',
                    'sub_title' => 'Equipment and Settings',
                    'body' => 'Equipment and Settings',
                    'order_number' =>2,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' =>2,
                    'language' => 'english',
                ),
                array(
                    'title' => 'Emotion and Art',
                    'sub_title' => 'Emotion and Art',
                    'body' => 'Emotion and Art',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 3,
                    'language' => 'english',
                ),
                array(
                    'title' => 'Basic Principles of Graphic Design',
                    'sub_title' => 'Basic Principles of Graphic Design',
                    'body' => 'Basic Principles of Graphic Design',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 3,
                    'language' => 'english',
                ),
                array(
                    'title' => 'The Rough Sketch',
                    'sub_title' => 'The Rough Sketch',
                    'body' => 'The Rough Sketch',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 4,
                    'language' => 'english',
                ),
                array(
                    'title' => 'Pitching & Final Thoughts',
                    'sub_title' => 'Pitching & Final Thoughts',
                    'body' => 'Pitching & Final Thoughts',
                    'order_number' =>2,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 4,
                    'language' => 'english',
                ),
                array(
                    'title' => 'The 6 Steps for Writing Success',
                    'sub_title' => 'The 6 Steps for Writing Success',
                    'body' => 'The 6 Steps for Writing Success',
                    'order_number' =>1,
                    'promo_video' => 'https://vimeo.com/',
                    'status' => 'PUBLISHED',
                    'program_id' => 5,
                    'language' => 'english',
                ),
            );
            foreach(Program::all() as $program)
            {
                $order_number = 1;
                foreach($chapters as $chapter)
                {
                    $createdChapter = new Chapter();
                    $createdChapter->title = $chapter['title'];
                    $createdChapter->sub_title = $chapter['sub_title']; 
                    $createdChapter->body = $chapter['body']; 
                    $createdChapter->order_number = $order_number++; 
                    $createdChapter->promo_video = $chapter['promo_video'];
                    $createdChapter->status = $chapter['status'];
                    $createdChapter->program_id = $program->id;
                    $createdChapter->language = $chapter['language'];
                    $createdChapter->save();
                }
            }
        }
    }
}
