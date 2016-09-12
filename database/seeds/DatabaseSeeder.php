<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Model::unguard();

        $this->call('UserSeeder');

        $this->call('UnitSeeder');

        //exercises
        $this->call('ExerciseSeriesSeeder');
        $this->call('ExerciseProgramSeeder');

        $this->call('ExerciseSeeder');

        $this->call('ExerciseEntrySeeder');

        $this->call('WorkoutSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
