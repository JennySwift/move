<?php

namespace Tests\Unit;

use App\Models\Exercise;
use App\Models\Workout;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use DB;


class WorkoutsExercisesTest extends TestCase
{
    use DatabaseTransactions;

    //api/workouts/{workout}/exercises/{exercise}
    private $url;
    private $workout;

    /**
     *
     * @param $workout
     * @return string
     */
    private function getUrl($workout)
    {
        return 'api/workouts/' . $workout->id . '/exercises';
    }

    /**
     * This is for if the workout already has the exercise
     * @test
     */
    public function it_can_update_the_sets_for_just_one_exercise_in_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $exercise = $workout->exercises()->first();

        $this->assertCount(10, $workout->groups);

        //Check the number of sets (every exercise) in the workout
        $this->assertCount(17, $workout->exercises()->get());

        $setsForExercise = $workout->exercises()->where('exercise_id', $exercise->id)->wherePivot('unit_id', 1)->get();

        //Check the number of sets (just one exercise) in the workout
        $this->assertCount(4, $setsForExercise);

        $this->assertEquals(5, $setsForExercise[0]->pivot->level);
        $this->assertEquals(50, $setsForExercise[0]->pivot->quantity);

        $response = $this->call('PUT', $this->getUrl($workout) . '/' . $exercise->id . '?include=exercises', [
            'name' => 'numbat',
            'exercise_id' => $exercise->id,
            'unit_id' => 1,
            'exercises' => [
                [
                    'level' => 52,
                    'quantity' => 60,
                    'workout_group_id' => 1
                ],
                [
                    'level' => 15,
                    'quantity' => 140,
                    'workout_group_id' => 1
                ]
            ]
        ]);
        $content = $this->getContent($response);
//dd($content);
        $this->checkWorkoutKeysExist($content);
        $this->assertArrayHasKey('exercises', $content);
        $exercises = $content['exercises']['data'];
        $this->checkExerciseWorkoutKeysExist($exercises[0]);

        //Check only one group was created
        $workout = Workout::forCurrentUser()->first();
        $this->assertCount(10, $workout->groups);

        //Check the other exercises didn't get detached
        $this->assertCount(15, $exercises);

        $setsForExercise = $workout->exercises()->where('exercise_id', 1)->wherePivot('unit_id', 1)->get();

        //Check the number of sets (just one exercise) in the workout
        $this->assertCount(2, $setsForExercise);

        //Check the data for the updated exercise was updated correctly
        $this->assertEquals($exercise->id, $exercises[13]['exercise_id']);
        $this->assertEquals(52, $exercises[13]['level']);
        $this->assertEquals(60, $exercises[13]['quantity']);
        $this->assertEquals(1, $exercises[13]['unit']['data']['id']);

        $this->assertEquals($exercise->id, $exercises[14]['exercise_id']);
        $this->assertEquals(15, $exercises[14]['level']);
        $this->assertEquals(140, $exercises[14]['quantity']);
        $this->assertEquals(1, $exercises[14]['unit']['data']['id']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * This is for if the workout does not already have the exercise
     * @test
     */
//    public function it_can_add_sets_for_just_one_exercise_in_a_workout()
//    {
//        $this->logInUser();
//
//        $workout = Workout::forCurrentUser()->first();
//        $exercise = Exercise::forCurrentUser()->skip(1)->first();
//        //Check the workout doesn't already have the exercise
//        $this->assertNull($workout->exercises()->where('exercise_id', $exercise->id)->first());
//
//        $this->assertCount(10, $workout->groups);
//
//        //Check the number of sets (every exercise) in the workout
//        $this->assertCount(17, $workout->exercises()->get());
//
//        $setsForExercise = $workout->exercises()->where('exercise_id', $exercise->id)->wherePivot('unit_id', 1)->get();
//
//        //Check the number of sets (just one exercise) in the workout
//        $this->assertCount(0, $setsForExercise);
//
//        $response = $this->call('PUT', $this->getUrl($workout) . '/' . $exercise->id . '?include=exercises', [
//            'name' => 'numbat',
//            'exercise_id' => $exercise->id,
//            'unit_id' => 1,
//            'exercises' => [
//                [
//                    'level' => 52,
//                    'quantity' => 60,
//                    'workout_group_id' => 1
//                ],
//                [
//                    'level' => 15,
//                    'quantity' => 140,
//                    'workout_group_id' => 1
//                ]
//            ]
//        ]);
//        $content = $this->getContent($response);
////dd($content);
//        $this->checkWorkoutKeysExist($content);
//        $this->assertArrayHasKey('exercises', $content);
//        $exercises = $content['exercises']['data'];
//        $this->checkExerciseWorkoutKeysExist($exercises[0]);
//
//        //Check only one group was created
//        $workout = Workout::forCurrentUser()->first();
//        $this->assertCount(11, $workout->groups);
//
//        //Check the other exercises didn't get detached
//        $this->assertCount(15, $exercises);
//
//        $setsForExercise = $workout->exercises()->where('exercise_id', 1)->wherePivot('unit_id', 1)->get();
//
//        //Check the number of sets (just one exercise) in the workout
//        $this->assertCount(2, $setsForExercise);
//
//        //Check the data for the updated exercise was updated correctly
//        $this->assertEquals($exercise->id, $exercises[13]['exercise_id']);
//        $this->assertEquals(52, $exercises[13]['level']);
//        $this->assertEquals(60, $exercises[13]['quantity']);
//        $this->assertEquals(1, $exercises[13]['unit']['data']['id']);
//
//        $this->assertEquals($exercise->id, $exercises[14]['exercise_id']);
//        $this->assertEquals(15, $exercises[14]['level']);
//        $this->assertEquals(140, $exercises[14]['quantity']);
//        $this->assertEquals(1, $exercises[14]['unit']['data']['id']);
//
//        $this->assertEquals(200, $response->getStatusCode());
//    }

    /**
     * Not needed anymore I think
     * @test
     */
    public function it_creates_a_new_workout_group_and_updates_the_sets_for_just_one_exercise_if_no_workout_group_id_is_given()
    {
        $this->markTestSkipped();
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $exercise = Exercise::forCurrentUser()->first();

        $this->assertCount(10, $workout->groups);

        //Check the number of sets (every exercise) in the workout
        $this->assertCount(17, $workout->exercises()->get());

        $setsForExercise = $workout->exercises()->where('exercise_id', $exercise->id)->wherePivot('unit_id', 1)->get();

        //Check the number of sets (just one exercise) in the workout
        $this->assertCount(4, $setsForExercise);

        $this->assertEquals(5, $setsForExercise[0]->pivot->level);
        $this->assertEquals(50, $setsForExercise[0]->pivot->quantity);

        $response = $this->call('PUT', $this->getUrl($workout) . '/' . $exercise->id . '?include=exercises', [
            'name' => 'numbat',
            'exercise_id' => 1,
            'unit_id' => 1,
            'exercises' => [
                [
                    'level' => 52,
                    'quantity' => 60,
                    'workout_group_id' => null
                ],
                [
                    'level' => 15,
                    'quantity' => 140,
                    'workout_group_id' => null
                ],
                [
                    'level' => 15,
                    'quantity' => 140,
                    'workout_group_id' => null
                ]
            ]
        ]);
        $content = $this->getContent($response);
//dd($content);

        //Check the new workout groups were created for the workout
        //and that only one group was created for the two sets of the same exercise
        $this->assertCount(12, Workout::forCurrentUser()->first()->groups);

        $this->checkWorkoutKeysExist($content);
        $this->assertArrayHasKey('exercises', $content);
        $exercises = $content['exercises']['data'];
        $this->checkExerciseWorkoutKeysExist($exercises[0]);

        //Check the other exercises didn't get detached
        $this->assertCount(15, $exercises);

        $setsForExercise = $workout->exercises()->where('exercise_id', 1)->wherePivot('unit_id', 1)->get();

        //Check the number of sets (just one exercise) in the workout
        $this->assertCount(2, $setsForExercise);

        //Check the data for the updated exercise was updated correctly
        $this->assertEquals(1, $exercises[13]['exercise_id']);
        $this->assertEquals(52, $exercises[13]['level']);
        $this->assertEquals(60, $exercises[13]['quantity']);
        $this->assertEquals(1, $exercises[13]['unit']['data']['id']);

        $this->assertEquals(1, $exercises[14]['exercise_id']);
        $this->assertEquals(15, $exercises[14]['level']);
        $this->assertEquals(140, $exercises[14]['quantity']);
        $this->assertEquals(1, $exercises[14]['unit']['data']['id']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * For if the workout doesn't have the exercise already
     * @test
     */
//    public function it_can_add_a_new_group_with_new_exercise_that_workout_doesnt_have_to_a_workout()
//    {
//        $this->logInUser();
//
//        $workout = Workout::forCurrentUser()->first();
//
//        $exercise = Exercise::forCurrentUser()->skip(1)->first();
//        //Check the workout doesn't already have the exercise
//        $this->assertNull($workout->exercises()->where('exercise_id', $exercise->id)->first());
//
//        $this->assertCount(10, $workout->groups);
//        $this->assertCount(17, $workout->exercises);
//
//        $response = $this->call('POST', $this->getUrl($workout) . '?include=exercises', [
//            'exercise_id' => $exercise->id,
//            'level' => 2,
//            'quantity' => 34,
//            'unit_id' => 1,
//            'workout_group_id' => null
//        ]);
//        $content = $this->getContent($response);
////      dd($content);
//
//        $this->checkWorkoutKeysExist($content);
//        $this->assertArrayHasKey('exercises', $content);
//        $exercises = $content['exercises']['data'];
//        $this->checkExerciseWorkoutKeysExist($exercises[0]);
//
//        //Check the exercises are as expected
//        $this->assertEquals($exercise->id, $exercises[17]['exercise_id']);
//        $this->assertEquals(2, $exercises[17]['level']);
//        $this->assertEquals(34, $exercises[17]['quantity']);
//        $this->assertEquals(1, $exercises[17]['unit']['data']['id']);
//        $this->assertNotNull($exercises[17]['workoutGroup']['data']['id']);
//        $this->assertEquals(11, $exercises[17]['workoutGroup']['data']['order']);
//
//        //Check a new group was created
//        $workout = Workout::forCurrentUser()->first();
//        $this->assertCount(11, $workout->groups);
//        //Check the exercise count for the workout has increased by one
//        $this->assertCount(18, $workout->exercises);
//
//        $this->assertCount(18, $exercises);
//
//        $this->assertEquals(201, $response->getStatusCode());
//    }


    /**
     * I think this is for if the workout already has the exercise
     * @test
     */
    public function it_can_add_a_new_group_with_new_exercise_to_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();

        $exercise = Exercise::forCurrentUser()->first();
        $this->assertCount(10, $workout->groups);
        $this->assertCount(17, $workout->exercises);

        $response = $this->call('POST', $this->getUrl($workout) . '?include=exercises', [
            'exercise_id' => $exercise->id,
            'level' => 2,
            'quantity' => 34,
            'unit_id' => 1,
            'workout_group_id' => null
        ]);
        $content = $this->getContent($response);
//      dd($content);

        $this->checkWorkoutKeysExist($content);
        $this->assertArrayHasKey('exercises', $content);
        $exercises = $content['exercises']['data'];
        $this->checkExerciseWorkoutKeysExist($exercises[0]);

        //Check the exercises are as expected
        $this->assertEquals($exercise->id, $exercises[17]['exercise_id']);
        $this->assertEquals(2, $exercises[17]['level']);
        $this->assertEquals(34, $exercises[17]['quantity']);
        $this->assertEquals(1, $exercises[17]['unit']['data']['id']);
        $this->assertNotNull($exercises[17]['workoutGroup']['data']['id']);
        $this->assertEquals(11, $exercises[17]['workoutGroup']['data']['order']);

        //Check a new group was created
        $workout = Workout::forCurrentUser()->first();
        $this->assertCount(11, $workout->groups);
        //Check the exercise count for the workout has increased by one
        $this->assertCount(18, $workout->exercises);

        $this->assertCount(18, $exercises);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     *
     * @test
     */
    public function it_can_delete_an_exercise_set_in_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $exercise = Exercise::forCurrentUser()->first();

        $setsForExercise = $workout->exercises()->where('exercise_id', $exercise->id)->wherePivot('unit_id', 1)->get();

        //Check the number of sets (just one exercise) in the workout
        $this->assertCount(4, $setsForExercise);

        //Check the set is in the database
        $setToDelete = DB::table('exercise_workout')->find($setsForExercise[0]->id);
        $this->assertNotNull($setToDelete);

        $url = $this->getUrl($workout) . '/' . $exercise->id . '?include=exercises';
        $response = $this->call('DELETE', $url,[
            'id' => $setToDelete->id
        ]);
        $this->assertEquals(204, $response->getStatusCode());

        $setsForExercise = $workout->exercises()->where('exercise_id', $exercise->id)->wherePivot('unit_id', 1)->get();
        $this->assertCount(3, $setsForExercise);

        //Check the set is no longer in the database
        $setToDelete = DB::table('exercise_workout')->find($setsForExercise[0]->id);
        $this->assertNull($setToDelete);
    }

    private function createWorkout()
    {
        $this->logInUser();

        $workout = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', $this->url, $workout);
        $content = $this->getContent($response);
//         dd($content);

        $this->checkWorkoutKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        return Workout::find($content['id']);
    }

}