<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
        // $this->call(TestSeeder::class);
        // $this->call(SponsorSeeder::class);
        // $this->call(ProgramSeeder::class);
        // $this->call(ChapterSeeder::class);
        // $this->call(ContentSeeder::class);
        // $this->call(DiscussionSeeder::class);
        // $this->call(EnrollmentSeeder::class);
        // $this->call(FavouriteProgramSeeder::class);
        // $this->call(FollowerSeeder::class);
        // $this->call(LikeSeeder::class);
        // $this->call(TaskSeeder::class);
        // $this->call(ProgramCategorySeeder::class);
        // $this->call(ProgramMentorSeeder::class);
        // $this->call(ProgramSponsorSeeder::class);
        // $this->call(QuestionAnswerSeeder::class);
        // $this->call(TestimonialSeeder::class);
        // $this->call(TestQuestionSeeder::class);
        // $this->call(UserAnswerSeeder::class);
        // $this->call(UserInterestSeeder::class);
        // $this->call(UserTaskViewSeeder::class);
        // $this->call(WatchContentSeeder::class);
    }
}
