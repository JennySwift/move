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
                            'exercise_id' => $exerciseIds[5],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $exerciseIds[5],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ]
                    ]
                ],
                [
                    'name' => 'Hanging',
                    'exercises' => [
                        [
                            'exercise_id' => $exerciseIds[8],
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 120
                        ],
                        [
                            'exercise_id' => $exerciseIds[9],
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 120
                        ]
                    ]
                ],
                [
                    'name' => 'Pulling',
                    'exercises' => [
                        [
                            'exercise_id' => $exerciseIds[0],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $exerciseIds[0],
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ]
                    ]
                ],
                [
                    'name' => 'Pushing',
                    'exercises' => [
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
}