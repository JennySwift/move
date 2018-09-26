<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkoutGroupFieldToExerciseWorkoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exercise_workout', function (Blueprint $table) {
            $table->integer('workout_group_id')->nullable()->unsigned()->index();
            $table->foreign('workout_group_id')->references('id')->on('workout_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercise_workout', function (Blueprint $table) {
            //
        });
    }
}
