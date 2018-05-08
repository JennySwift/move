<?php

use App\Models\Exercise;
use App\Models\Unit;
use App\Models\Workout;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;

class WorkoutSeeder extends Seeder {

    private $user;
    private $faker;

    public function run()
	{
		Workout::truncate();
        $this->faker = Faker::create();

        foreach(User::all() as $user) {
            $this->user = $user;
            $exerciseIds = Exercise::where('user_id', $this->user->id)->pluck('id')->all();
            $unitIds = Unit::where('user_id', $this->user->id)->pluck('id')->all();

            $workouts = [
                [
                    'name' => 'Upper Body',
                    'exercises' => [
                        [
                            'exercise_id' => $exerciseIds[0],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $exerciseIds[0],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $exerciseIds[0],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $exerciseIds[1],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $exerciseIds[2],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $exerciseIds[2],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ]
                    ]
                ],
                [
                    'name' => 'Lower Body',
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('L-Sit'),
                            'unit_id' => $unitIds[0],
                            'level' => 5,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('L-Sit'),
                            'unit_id' => $unitIds[0],
                            'level' => 5,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('L-Sit'),
                            'unit_id' => $unitIds[0],
                            'level' => 10,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('L-Sit'),
                            'unit_id' => $unitIds[0],
                            'level' => 6,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Hanging Knee Raise'),
                            'unit_id' => $unitIds[0],
                            'level' => 5,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Hollow Body Hold'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 120
                        ],
                        [
                            'exercise_id' => $this->getExercise('Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Reverse Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Short Bridge'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Tabletop Bridge'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 300
                        ],
                        [
                            'exercise_id' => $this->getExercise('Narrow Stance Full Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 2,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Narrow Stance Full Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 2,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Plank'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 180
                        ],
                    ]
                ],
                [
                    'name' => 'Hanging',
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Overhand'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 300
                        ],
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Underhand'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 180
                        ],
                    ]
                ],
                [
                    'name' => 'Pulling',
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand'),
                            'unit_id' => $unitIds[0],
                            'level' => 4,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Overhand'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Wide Grip'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Row'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 50
                        ],
                    ]
                ],
                [
                    'name' => 'Scapular Row',
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Scapular Row'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Row'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Row'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 30
                        ]
                    ]
                ],
                [
                    'name' => 'Upper Body',
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Underhand)'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 180
                        ],
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Overhand)'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 300
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup (Reverse Grip)'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup (Wide Grip)'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand'),
                            'unit_id' => $unitIds[0],
                            'level' => 4,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Overhand'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Wide Grip'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Row'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 50
                        ],
                    ]
                ],
            ];

            $index = 0;
            foreach($workouts as $workout) {
                $index++;
                $temp = new Workout([
                    'name' => $workout['name'],
                ]);


                $temp->user()->associate($this->user);

                $temp->save();

                foreach ($workout['exercises'] as $exercise) {
                    $temp->exercises()->attach($exercise['exercise_id'],
                        [
                            'level' => $exercise['level'],
                            'unit_id' => $exercise['unit_id'],
                            'quantity' => $exercise['quantity']
                        ]);
                }
            }

        }

	}

    /**
     *
     * @param $string
     * @return mixed
     */
    private function getExercise($string)
    {
        return Exercise::forCurrentUser()->where('name', $string)->get();
    }
}