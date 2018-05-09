<?php

use App\Models\Session;
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
        $this->faker = Faker::create();

        foreach (User::all() as $user) {
            $this->user = $user;

            $sessions = [
                ['name' => 'Lower Body', 'daysAgo' => 0],
                ['name' => 'Upper Body', 'daysAgo' => 1],
                ['name' => 'Hanging', 'daysAgo' => 1],
                ['name' => 'Lower Body', 'daysAgo' => 2],
                ['name' => 'Upper Body', 'daysAgo' => 3],
                ['name' => 'Lower Body', 'daysAgo' => 4],
                ['name' => 'Hanging', 'daysAgo' => 4],
                ['name' => 'Upper Body', 'daysAgo' => 5],
                ['name' => 'Lower Body', 'daysAgo' => 6],
                ['name' => 'Upper Body', 'daysAgo' => 7],
                ['name' => 'Lower Body', 'daysAgo' => 9],
                ['name' => 'Hanging', 'daysAgo' => 10],
                ['name' => 'Upper Body', 'daysAgo' => 13],
                ['name' => 'Hanging', 'daysAgo' => 14],
                ['name' => 'Lower Body', 'daysAgo' => 15],
                ['name' => 'Reverse Grip Pushups', 'daysAgo' => 16],
                ['name' => 'Upper Body', 'daysAgo' => 17],
                ['name' => 'Lower Body', 'daysAgo' => 18],
                ['name' => 'Upper Body', 'daysAgo' => 19],
                ['name' => 'Lower Body', 'daysAgo' => 20],
                ['name' => 'Upper Body', 'daysAgo' => 21],
                ['name' => 'Scapular Row', 'daysAgo' => 22],
                ['name' => 'Scapular Row', 'daysAgo' => 23],
                ['name' => 'Short Bridge', 'daysAgo' => 24],
                ['name' => 'Pulling', 'daysAgo' => 25],
            ];

            $today = Carbon:: today();
            foreach ($sessions as $session) {
                $temp = new Session([
                    'name' => $session['name'],
                    'created_at' => $today->copy()->subDays($session['daysAgo'])->format('Y-m-d H:i:s')
                ]);


                $temp->user()->associate($this->user);

                $temp->save();

//                foreach ($session['exercises'] as $exercise) {
//                    $temp->exercises()->attach($exercise['exercise_id'],
//                        [
//                            'level' => $exercise['level'],
//                            'unit_id' => $exercise['unit_id'],
//                            'quantity' => $exercise['quantity']
//                        ]);
//                }
            }

        }

    }
}