<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkoutGroupFieldToExerciseSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exercise_session', function (Blueprint $table) {
            $table->integer('workout_group_id')->nullable()->unsigned()->index();
            $table->foreign('workout_group_id')->references('id')->on('workout_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercise_session', function (Blueprint $table) {
            //
        });
    }
}
