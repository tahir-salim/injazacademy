<?php

namespace Database\Seeders;

use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programs = array(

            array(
                'title' => 'Landscape Photography',
                'sub_title' => 'How to Capture the Beauty of Planet Earth',
                'body' => 'Love taking photos outside? Join travel photographer Sean Dalton
                in Bali.',
                'duration' =>'3h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'PUBLISHED',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'arabic',

            ),
            array(
                'title' => 'DSLR photography',
                'sub_title' => 'Understanding Lenses, Focal Length & Shooting',
                'body' => 'Take total control of your images with this DSLR photography
                class perfect for enthusiasts of all levels.',
                'duration' =>'2h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'arabic',

            ),
            array(
                'title' => 'Great Graphic Design',
                'sub_title' => 'Create Emotional, Gripping Typographic Art',
                'body' => 'Join Sophia in a constructive and charming class that
                breaks down the fundamentals of graphic design.',
                'duration' =>'5h 31m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => false,
                'issue_certificate' => false,
                'task_required' => true,
                'available_languages' => 'English',

            ),
            array(
                'title' => 'Make Your Voice Heard',
                'sub_title' => 'Write a Personal and Persuasive Essay',
                'body' => 'Many of us have strong opinions right now—personal and
                political beliefs we want to express',
                'duration' =>'1h 30m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'English',

            ),
            array(
                'title' => 'The Writers Toolkit',
                'sub_title' => '6 Steps to a Successful Writing Habit',
                'body' => 'Do you dream of being a writer but are struggling
                with how to start?',
                'duration' =>'3h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'english',
            ),
            array(
                'title' => 'The Vendors Toolkit',
                'sub_title' => '6 Steps to a Successful Writing Habit',
                'body' => 'Do you dream of being a writer but are struggling
                with how to start?',
                'duration' =>'3h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'english',
            ),
            array(
                'title' => 'The Vendors Kahns',
                'sub_title' => '6 Steps to a Successful Writing Habit',
                'body' => 'Do you dream of being a writer but are struggling
                with how to start?',
                'duration' =>'3h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'english',
            ),
            array(
                'title' => 'The Shazam Khan',
                'sub_title' => '6 Steps to a Successful Writing Habit',
                'body' => 'Do you dream of being a writer but are struggling
                with how to start?',
                'duration' =>'3h 6m',
                'promo_video'=>'https://vimeo.com/',
                'is_workshop' => false,
                'is_live' => false,
                'live_date_time' => Carbon::now(),
                'status' => 'DRAFT',
                'generate_linkedin_certificate' => true,
                'issue_certificate' => true,
                'task_required' => true,
                'available_languages' => 'english',
            ),

        );
        foreach ($programs as $program){
            $createdprogram = new Program();
            $createdprogram->title = $program['title'];
            $createdprogram->sub_title = $program['sub_title'];
            $createdprogram->body = $program['body'];
            $createdprogram->promo_video = $program['promo_video'];
            $createdprogram->duration = $program['duration'];
            $createdprogram->is_workshop = $program['is_workshop'];
            $createdprogram->is_live = $program['is_live'];
            $createdprogram->live_date_time = $program['live_date_time'];
            $createdprogram->status = $program['status'];
            $createdprogram->generate_linkedin_certificate = $program['generate_linkedin_certificate'];
            $createdprogram->issue_certificate = $program['issue_certificate'];
            $createdprogram->task_required = $program['task_required'];
            $createdprogram->available_languages = $program['available_languages'];
            $createdprogram->created_at = Carbon::now();
            $createdprogram->updated_at = Carbon::now();
            $createdprogram->save();
            $createdprogram->categories()->sync([6,7,8,9]);

        }
//        DB::table('programs')->insert($programs);
    }
}
