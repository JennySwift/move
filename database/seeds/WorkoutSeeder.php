<?php

use App\Models\Exercise;
use App\Models\Unit;
use App\Models\Workout;
use App\Models\WorkoutGroup;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{

    private $user;
    private $faker;
    private $unitIds;
    private $exerciseIds;

    public function run()
    {
        Workout::truncate();
        WorkoutGroup::truncate();
        DB::table('exercise_workout')->truncate();
        $this->faker = Faker::create();

        foreach (User::all() as $user) {
            $this->user = $user;
            $this->exerciseIds = Exercise::where('user_id', $this->user->id)->pluck('id')->all();
            $this->unitIds = Unit::where('user_id', $this->user->id)->pluck('id')->all();

            $this->createWorkouts();
        }

    }

    /**
     *
     * @param $string
     * @return mixed
     */
    private function getExercise($string)
    {
        $exercise = Exercise::where('user_id', $this->user->id)->where('name', $string)->firstOrFail();

        return $exercise->id;
    }

    /**
     *
     */
    private function createWorkouts(): void
    {
        $workouts = [
            [
                'name' => 'Lower Body',
                'groups' => [
                    [
                        'order' => 1,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('L-Sit'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 5,
                                'quantity' => 50,
                            ],
                            [
                                'exercise_id' => $this->getExercise('L-Sit'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 5,
                                'quantity' => 50,
                            ],
                            [
                                'exercise_id' => $this->getExercise('L-Sit'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 10,
                                'quantity' => 100,
                            ],
                            [
                                'exercise_id' => $this->getExercise('L-Sit'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 6,
                                'quantity' => 50,
                            ],
                        ]
                    ],
                    [
                        'order' => 2,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Hanging Knee Raise'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 5,
                                'quantity' => 100,
                            ],
                        ]
                    ],
                    [
                        'order' => 3,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Hollow Body Hold'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 120,
                            ],
                        ]
                    ],
                    [
                        'order' => 4,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Hyperextension'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 1,
                                'quantity' => 50,
                            ],
                            [
                                'exercise_id' => $this->getExercise('Hyperextension'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 1,
                                'quantity' => 50,
                            ],
                        ]
                    ],
                    [
                        'order' => 5,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Reverse Hyperextension'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 1,
                                'quantity' => 50
                            ],
                        ]
                    ],
                    [
                        'order' => 6,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Short Bridge'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 1,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 7,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Tabletop Bridge'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 300
                            ],
                        ]
                    ],
                    [
                        'order' => 8,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Narrow Stance Full Squat'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 2,
                                'quantity' => 100
                            ],
                            [
                                'exercise_id' => $this->getExercise('Narrow Stance Full Squat'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 2,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 9,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Box Squat'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 15,
                                'quantity' => 50,
                                'order' => 1
                            ],
                            [
                                'exercise_id' => $this->getExercise('Box Squat'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 15,
                                'quantity' => 30,
                                'order' => 1
                            ],
                            [
                                'exercise_id' => $this->getExercise('Box Squat'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 15,
                                'quantity' => 30,
                                'order' => 1
                            ],
                        ]
                    ],
                    [
                        'order' => 10,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Plank'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 180
                            ],
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Hanging',
                'groups' => [
                    [
                        'order' => 2,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Bar Hang (Overhand)'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 300
                            ],
                        ]
                    ],
                    [
                        'order' => 1,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Bar Hang (Underhand)'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 180
                            ],
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Pulling',
                'groups' => [
                    [
                        'order' => 1,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 4,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 2,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Overhand)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 3,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Wide Grip)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 4,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Scapular Row'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 50
                            ],
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Scapular Row',
                'groups' => [
                    [
                        'order' => 1,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Scapular Row'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 30
                            ],
                            [
                                'exercise_id' => $this->getExercise('Scapular Row'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 30
                            ],
                            [
                                'exercise_id' => $this->getExercise('Scapular Row'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 30
                            ]
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Upper Body',
                'groups' => [
                    [
                        'order' => 1,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Bar Hang (Underhand)'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 180
                            ],
                        ]
                    ],
                    [
                        'order' => 2,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Bar Hang (Overhand)'),
                                'unit_id' => $this->unitIds[1],
                                'level' => 1,
                                'quantity' => 300
                            ],
                        ]
                    ],
                    [
                        'order' => 3,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Incline Pushup (Reverse Grip)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 33,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 4,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Incline Pushup'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 33,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 5,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Incline Pushup (Wide Grip)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 33,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 6,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Scapular Pushup'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 33,
                                'quantity' => 50
                            ],
                        ]
                    ],
                    [
                        'order' => 7,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 4,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 8,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Overhand)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 9,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Inverted Row (Wide Grip)'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 100
                            ],
                        ]
                    ],
                    [
                        'order' => 10,
                        'exercises' => [
                            [
                                'exercise_id' => $this->getExercise('Scapular Row'),
                                'unit_id' => $this->unitIds[0],
                                'level' => 3,
                                'quantity' => 50
                            ],
                        ]
                    ]
                ],
            ],
        ];

        foreach ($workouts as $workout) {
            $tempWorkout = new Workout([
                'name' => $workout['name'],
            ]);


            $tempWorkout->user()->associate($this->user);

            $tempWorkout->save();


            foreach ($workout['groups'] as $workoutGroup) {
                $tempWorkoutGroup = new WorkoutGroup([
                    'order' => $workoutGroup['order'],
                ]);


                $tempWorkoutGroup->workout()->associate($tempWorkout);

                $tempWorkoutGroup->save();

                foreach ($workoutGroup['exercises'] as $exercise) {
                    $order = isset($exercise['order']) ? $exercise['order'] : null;
                    $tempWorkout->exercises()->attach($exercise['exercise_id'],
                        [
                            'level' => $exercise['level'],
                            'unit_id' => $exercise['unit_id'],
                            'quantity' => $exercise['quantity'],
                            'order' => $order,
                            'workout_group_id' => $tempWorkoutGroup->id
                        ]);
                }
            }


        }
    }

}