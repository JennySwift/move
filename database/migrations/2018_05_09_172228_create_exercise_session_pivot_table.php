<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseSessionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_session', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('exercise_id')->unsigned()->index();
            $table->integer('unit_id')->unsigned()->index();

            $table->integer('level');
            $table->integer('quantity');

            $table->timestamps();

            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercise_session');
    }
}
