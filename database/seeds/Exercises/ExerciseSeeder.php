<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercise;
use App\Models\Unit;
use App\Models\Series;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;

class ExerciseSeeder extends Seeder {

    private $user;
    private $faker;

    public function run()
	{
		Exercise::truncate();
        $this->faker = Faker::create();

		$exercises= [
            [
                'name' => 'L-Sit',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'Hanging Knee Raise',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'Hollow Body Hold',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'Hyperextension',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'Reverse Hyperextension',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'Short Bridge',
                'description' => '',
                'priority' => 1
            ],
            [
                'name' => 'Tabletop Bridge',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Narrow Stance Full Squat',
                'description' => '',
                'priority' => 3,
            ],
            [
                'name' => 'Box Squat',
                'description' => '',
                'priority' => 2
            ],
            [
                'name' => 'Plank',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Bar Hang (Overhand)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Bar Hang (Underhand)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Inverted Row (Underhand)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Inverted Row (Overhand)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Inverted Row (Wide Grip)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Scapular Row',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Incline Pushup',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Incline Pushup (Reverse Grip)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Incline Pushup (Wide Grip)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'Scapular Pushup',
                'description' => '',
                'priority' => 1,
            ],
        ];

        $users = User::all();

        foreach($users as $user) {
            $this->user = $user;

            $this->insertExercises($exercises);
        }

	}

    /**
     *
     * @param $exercises
     */
    private function insertExercises($exercises)
    {
        $index = 0;

        foreach ($exercises as $exercise) {
            $index++;
            $temp = new Exercise([
                'name' => $exercise['name'],
                'description' => $exercise['description'],
                'priority' => $exercise['priority'],
            ]);


            $temp->user()->associate($this->user);

            $temp->save();

        }
    }

}