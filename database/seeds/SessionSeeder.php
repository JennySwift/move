<?php

use App\Models\Exercise;
use App\Models\Session;
use App\Models\Unit;
use App\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{

    private $user;
    private $faker;

    public function run()
    {
        Session::truncate();
        DB::table('exercise_session')->truncate();
        $this->faker = Faker::create();

        foreach (User::all() as $user) {
            $this->user = $user;
            $unitIds = Unit::where('user_id', $this->user->id)->pluck('id')->all();

            $sessions = [
                [
                    'name' => 'Lower Body',
                    'daysAgo' => 0,
                    'exercises' => [
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
                    ]
                ],
                [
                    'name' => 'Upper Body',
                    'daysAgo' => 1,
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Chinup'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 4,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 5,
                            'quantity' => 100
                        ],
                    ]
                ]
                ,
                [
                    'name' => 'Lower Body',
                    'daysAgo' => 3,
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 18,
                            'quantity' => 25
                        ],
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 75
                        ]
                    ]
                ],
                [
                    'name' => 'Upper Body',
                    'daysAgo' => 4,
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 30
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 50
                        ],
                    ]
                ],
                [
                    'name' => 'Lower Body',
                    'daysAgo' => 5,
                    'exercises' => [
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
                            'exercise_id' => $this->getExercise('Reverse Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 1,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 18,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Reverse Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 10
                        ]
                    ]
                ],
                [
                    'name' => 'Upper Body',
                    'daysAgo' => 6,
                    'exercises' => [
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Underhand)'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 240
                        ],
                        [
                            'exercise_id' => $this->getExercise('Bar Hang (Overhand)'),
                            'unit_id' => $unitIds[1],
                            'level' => 3,
                            'quantity' => 150
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 33,
                            'quantity' => 100
                        ],
                    ]
                ],
                [
                    'name' => 'Lower Body',
                    'daysAgo' => 7,
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
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Hollow Body Hold'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 120
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
                            'exercise_id' => $this->getExercise('Box Squat'),
                            'unit_id' => $unitIds[0],
                            'level' => 18,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Reverse Hyperextension'),
                            'unit_id' => $unitIds[0],
                            'level' => 15,
                            'quantity' => 10
                        ],
                        [
                            'exercise_id' => $this->getExercise('Plank'),
                            'unit_id' => $unitIds[1],
                            'level' => 1,
                            'quantity' => 180
                        ]
                    ]
                ],
                [
                    'name' => 'Upper Body',
                    'daysAgo' => 8,
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
                            'level' => 3,
                            'quantity' => 120
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 140
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup (Wide Grip)'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Incline Pushup (Reverse Grip)'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Scapular Pushup'),
                            'unit_id' => $unitIds[0],
                            'level' => 36,
                            'quantity' => 50
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 2,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 2,
                            'quantity' => 100
                        ],
                        [
                            'exercise_id' => $this->getExercise('Inverted Row (Underhand)'),
                            'unit_id' => $unitIds[0],
                            'level' => 3,
                            'quantity' => 100
                        ],
                    ]
                ]
//                [
//                    'name' => 'Hanging',
//                    'daysAgo' => 1]
//                ,
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 2]
//                ,
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 3]
//                ,
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 4]
//                ,
//                [
//                    'name' => 'Hanging',
//                    'daysAgo' => 4]
//                ,
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 5]
//                ,
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 6]
//                ,
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 7]
//                ,
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 9]
//                ,
//                [
//                    'name' => 'Hanging',
//                    'daysAgo' => 10
//                ],
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 13
//                ],
//                [
//                    'name' => 'Hanging',
//                    'daysAgo' => 14
//                ],
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 15
//                ],
//                [
//                    'name' => 'Reverse Grip Pushups',
//                    'daysAgo' => 16
//                ],
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 17
//                ],
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 18
//                ],
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 19
//                ],
//                [
//                    'name' => 'Lower Body',
//                    'daysAgo' => 20
//                ],
//                [
//                    'name' => 'Upper Body',
//                    'daysAgo' => 21
//                ],
//                [
//                    'name' => 'Scapular Row',
//                    'daysAgo' => 22
//                ],
//                [
//                    'name' => 'Scapular Row',
//                    'daysAgo' => 23
//                ],
//                [
//                    'name' => 'Short Bridge',
//                    'daysAgo' => 24
//                ],
//                [
//                    'name' => 'Pulling',
//                    'daysAgo' => 25
//                ],
            ];

            $today = Carbon:: today();
            foreach ($sessions as $session) {
                $temp = new Session([
                    'name' => $session['name'],
                    'created_at' => $today->copy()->subDays($session['daysAgo'])->format('Y-m-d H:i:s')
                ]);


                $temp->user()->associate($this->user);

                $temp->save();

                foreach ($session['exercises'] as $exercise) {
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
        $exercise = Exercise::where('user_id', $this->user->id)->where('name', $string)->first();
        if (!$exercise) {
            dd($string);
        }

        return $exercise->id;
    }
}