<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkoutGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('workout_id')->unsigned()->index();
            $table->foreign('workout_id')->references('id')->on('workouts')->onDelete('cascade');

//            $table->integer('exercise_id')->unsigned()->index();
//            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');

            $table->integer('order')->index();
            $table->unique(array('order', 'workout_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_groups');
    }
}
