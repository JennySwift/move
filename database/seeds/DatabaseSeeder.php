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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
//        Model::unguard();
        // $this->call(UsersTableSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(UnitSeeder::class);

        $this->call(ExerciseSeeder::class);

        $this->call(WorkoutSeeder::class);

        $this->call(SessionSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
