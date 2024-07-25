<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('team_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('profile_photo')->unsigned()->nullable();
            $table->string('employee_id');
            // $table->string('password');
            // $table->string('password_confirmation');
            $table->string('country_code')->default(+91);
            $table->string('phone_number');
            $table->string('department');
            $table->string('designation');
            $table->string('blood_group')->nullable();
            $table->string('dob')->nullable();
            $table->string('doj')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->boolean('banned')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('profile_photo')->references('id')->on('file_infos');
            $table->foreign('added_by')->references('id')->on('users');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_members');
    }
}
