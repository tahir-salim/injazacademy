<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title');
            $table->string('body');
            $table->string('promo_video');
            $table->string('duration');
            $table->integer('age_from')->nullable();
            $table->integer('age_to')->nullable();
            $table->boolean('is_workshop')->default(0);
            $table->boolean('is_live')->default(0);
            $table->timestamp('live_date_time')->nullable();
            $table->string('status');
            $table->boolean('generate_linkedin_certificate')->default(0);
            $table->boolean('issue_certificate')->default(0);
            $table->boolean('task_required')->default(0);
            $table->string('task')->nullable();
            $table->string('available_languages');
            $table->foreignId('test_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
