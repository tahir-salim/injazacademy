<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('ar_name')->after('last_name')->nullable();
            $table->foreignId('country_id')->nullable()->after('headline');
            $table->dropColumn('country');
            $table->string('company')->nullable()->after('dob');
            $table->string('occupation')->nullable()->after('dob');
            $table->string('experience')->nullable()->after('dob');
            $table->string('cpr')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('middle_name');
            $table->dropColumn('ar_name');
            $table->dropColumn('country_id');
            $table->string('country')->after('headline')->nullable();
            $table->dropColumn('company');
            $table->dropColumn('occupation');
            $table->dropColumn('experience');
            $table->integer('cpr')->nullable()->change();
        });
    }
}
