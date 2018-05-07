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
                'name' => 'inverted rows',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'kneeling pushups',
                'description' => '',
                'priority' => 2,
            ],
            [
                'name' => 'pushups',
                'description' => 'hands shoulder width',
                'priority' => 1
            ],
            [
                'name' => 'one-arm pushups',
                'description' => 'free hand behind back',
                'priority' => 1,
            ],
            [
                'name' => 'assisted squats',
                'description' => 'hold onto something',
                'priority' => 3,
            ],
            [
                'name' => 'squats',
                'description' => 'feet shoulder width',
                'priority' => 2
            ],
            [
                'name' => 'box squats',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'one-legged-squats',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'bar hang (overhand)',
                'description' => '',
                'priority' => 1,
            ],
            [
                'name' => 'bar hang (underhand)',
                'description' => '',
                'priority' => 1,
            ]
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