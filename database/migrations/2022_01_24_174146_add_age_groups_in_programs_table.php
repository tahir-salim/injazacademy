<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgeGroupsInProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('age_group')->nullable()->after('duration');
            $table->boolean('is_rtl')->default(0)->after('body');
            $table->renameColumn('Age_from' , 'age_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('age_group');
            $table->dropColumn('is_rtl');
            $table->renameColumn('age_from' , 'Age_from');
        });
    }
}
