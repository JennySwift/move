<?php

namespace Tests\Unit;

use App\Models\Workout;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class WorkoutsTest
 */
class WorkoutsTest extends TestCase
{
    use DatabaseTransactions;

    private $url = '/api/workouts/';

    /**
     * @test
     */
    public function it_gets_the_workouts()
    {
        $this->logInUser();
        $response = $this->call('GET', $this->url);
        $content = $this->getContent($response);
//      dd($content);

        $this->checkWorkoutKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_create_a_workout()
    {
        $this->createWorkout();

    }

    /**
     * @test
     */
    public function it_can_update_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $workout->id, [
            'name' => 'numbat'
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkWorkoutKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_update_the_exercises_for_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $workout->id . '?include=exercises', [
            'name' => 'numbat',
            'exercises' => [
                [
                    'id' => 1,
                    'level' => 52,
                    'quantity' => 60,
                    'unit_id' => 1
                ],
                [
                    'id' => 6,
                    'level' => 15,
                    'quantity' => 140,
                    'unit_id' => 2
                ],
                [
                    'id' => 6,
                    'level' => 15,
                    'quantity' => 140,
                    'unit_id' => 2
                ]
            ]
        ]);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkWorkoutKeysExist($content);
        $this->assertArrayHasKey('exercises', $content);
        $exercises = $content['exercises']['data'];
        $this->checkExerciseWorkoutKeysExist($exercises[0]);

        //Check the exercises are as expected
        $this->assertEquals(1, $exercises[0]['id']);
        $this->assertEquals(52, $exercises[0]['level']);
        $this->assertEquals(60, $exercises[0]['quantity']);
        $this->assertEquals(1, $exercises[0]['unit']['data']['id']);

        $this->assertEquals(6, $exercises[1]['id']);
        $this->assertEquals(15, $exercises[1]['level']);
        $this->assertEquals(140, $exercises[1]['quantity']);
        $this->assertEquals(2, $exercises[1]['unit']['data']['id']);

        $this->assertEquals(6, $exercises[2]['id']);
        $this->assertEquals(15, $exercises[2]['level']);
        $this->assertEquals(140, $exercises[2]['quantity']);
        $this->assertEquals(2, $exercises[2]['unit']['data']['id']);

        $this->assertCount(3, $exercises);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_delete_a_workout()
    {
        $this->logInUser();

        $workout = $this->createWorkout();

        $response = $this->call('DELETE', $this->url . $workout->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $this->url . $workout->id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_a_workout()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . $workout->id);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkWorkoutKeysExist($content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_a_workout_with_its_exercises()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . $workout->id . '?include=exercises');
        $content = $this->getContent($response);
//        dd($content);

        $this->checkWorkoutKeysExist($content);

        $this->assertArrayHasKey('exercises', $content);
        $this->checkExerciseWorkoutKeysExist($content['exercises']['data'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
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

    /**
     *
     * @param $exercise
     */
    private function checkExerciseWorkoutKeysExist($exercise)
    {
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('level', $exercise);
        $this->assertArrayHasKey('quantity', $exercise);
        $this->checkUnitKeysExist($exercise['unit']['data']);
    }


}