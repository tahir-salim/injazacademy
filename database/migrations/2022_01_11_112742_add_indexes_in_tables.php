<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('user_id');
        });
        Schema::table('chapters', function (Blueprint $table) {
            $table->index('program_id');
            $table->dropColumn('language');
            $table->dropColumn('chapter_image');
            $table->dropColumn('sub_title');
            $table->dropColumn('body');
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('chapter_id');
            $table->dropColumn('content_image');
        });
        Schema::table('favourite_programs', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('user_id');
        });
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('test_id');
            $table->index('is_workshop');
            $table->index('status');
        });
        Schema::table('program_tags', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('tag_id');
        });
        Schema::table('program_sponsors', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('sponsor_id');
        });
        Schema::table('program_categories', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('category_id');
        });
        Schema::table('discussions', function (Blueprint $table) {
            $table->index('enrollment_id');
            $table->index('user_id');
            $table->index('program_id');
            $table->index('reply_id');
        });
        Schema::table('followers', function (Blueprint $table) {
            $table->index('follower_id');
            $table->index('user_id');
        });
        Schema::table('program_mentors', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('mentor_id');
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['likeable_id','likeable_type']);
        });
        Schema::table('watch_contents', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('content_id');
            $table->index('program_id');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('enrollment_id');
        });
        Schema::table('user_task_views', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('enrollment_id');
        });
        Schema::table('task_histories', function (Blueprint $table) {
            $table->index(['historable_id','historable_type']);
            $table->index('program_id');
        });
        Schema::table('user_interests', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('category_id');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->index('program_id');
        });
        Schema::table('test_questions', function (Blueprint $table) {
            $table->index('test_id');
        });
        Schema::table('question_answers', function (Blueprint $table) {
            $table->index('question_id');
        });
        Schema::table('user_answers', function (Blueprint $table) {
            $table->index('enrollment_id');
            $table->index('answer_id');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->index('enrollment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['user_id']);
        });
        Schema::table('chapters', function (Blueprint $table) {
            $table->string('chapter_image')->after('title')->nullable();
            $table->string('language')->after('title')->nullable();
            $table->longText('body')->after('title')->nullable();
            $table->string('sub_title')->after('title')->nullable();
            $table->dropIndex(['program_id']);
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['chapter_id']);
            $table->string('content_image')->nullable();
        });
        Schema::table('favourite_programs', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['user_id']);
        });
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('test_id');
            $table->dropIndex(['is_workshop']);
            $table->dropIndex(['status']);
        });
        Schema::table('program_tags', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['tag_id']);
        });
        Schema::table('program_sponsors', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['sponsor_id']);
        });
        Schema::table('program_categories', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['category_id']);
        });
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['program_id']);
            $table->dropIndex(['reply_id']);
        });
        Schema::table('followers', function (Blueprint $table) {
            $table->dropIndex(['follower_id']);
            $table->dropIndex(['user_id']);
        });
        Schema::table('program_mentors', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['mentor_id']);
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['likeable_id','likeable_type']);
        });
        Schema::table('watch_contents', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['content_id']);
            $table->dropIndex(['program_id']);
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['enrollment_id']);
        });
        Schema::table('user_task_views', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['enrollment_id']);
        });
        Schema::table('task_histories', function (Blueprint $table) {
            $table->dropIndex(['historable_id','historable_type']);
            $table->dropIndex(['program_id']);
        });
        Schema::table('user_interests', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['category_id']);
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
        });
        Schema::table('test_questions', function (Blueprint $table) {
            $table->dropIndex(['test_id']);
        });
        Schema::table('question_answers', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
        });
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id']);
            $table->dropIndex(['answer_id']);
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id']);
        });
    }
}
