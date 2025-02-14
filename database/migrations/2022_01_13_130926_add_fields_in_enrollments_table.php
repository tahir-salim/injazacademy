<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->longText('testimonial')->nullable()->after('certification_date');
            $table->integer('review')->default(0)->nullable()->after('certification_date');
            $table->integer('is_review')->default(0)->after('certification_date');
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
            $table->dropColumn('testimonial');
            $table->dropColumn('review');
            $table->dropColumn('is_review');
        });
    }
}
