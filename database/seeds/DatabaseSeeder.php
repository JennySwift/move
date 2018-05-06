<?php

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
        //Code from old app
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
//        Model::unguard();
        // $this->call(UsersTableSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(UnitSeeder::class);

        //exercises
        $this->call(ExerciseSeriesSeeder::class);
        $this->call(ExerciseProgramSeeder::class);

        $this->call(ExerciseSeeder::class);

        $this->call(ExerciseEntrySeeder::class);

        $this->call(WorkoutSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
