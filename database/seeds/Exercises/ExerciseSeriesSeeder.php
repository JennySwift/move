<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Series;

class ExerciseSeriesSeeder extends Seeder {

	public function run()
	{
		Series::truncate();

        $users = User::all();

        foreach($users as $user) {
            DB::table('exercise_series')->insert([
                'name' => 'pushup',
                'user_id' => $user->id,
                'priority' => 1,
                'color' => '#FF530D'
            ]);

            DB::table('exercise_series')->insert([
                'name' => 'pullup',
                'user_id' => $user->id,
                'priority' => 1,
                'color' => '#73FF26'
            ]);

            DB::table('exercise_series')->insert([
                'name' => 'squat',
                'user_id' => $user->id,
                'priority' => 2,
                'color' => '#1F2FCC'
            ]);

            DB::table('exercise_series')->insert([
                'name' => 'gymnastic rings',
                'user_id' => $user->id,
                'priority' => 1
            ]);

            DB::table('exercise_series')->insert([
                'name' => 'flexibility',
                'user_id' => $user->id,
                'priority' => 1
            ]);
        }


	}

}