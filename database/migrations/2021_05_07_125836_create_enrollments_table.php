<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('program_id');
            $table->string('status');
            $table->foreignId('transaction_id')->nullable();
            $table->timestamp('started_date');
            $table->timestamp('finished_date')->nullable();
            $table->string('test_score')->nullable();
            $table->string('certificate_url')->nullable();
            $table->boolean('is_certified')->default(0);
            $table->timestamp('certification_date')->nullable();
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
        Schema::dropIfExists('enrollments');
    }
}
