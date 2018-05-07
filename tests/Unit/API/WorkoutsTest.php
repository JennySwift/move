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

    private function createWorkout()
    {
        $this->logInUser();

        $workout = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', $this->url, $workout);
        $content = $this->getContent($response);
        // dd($content);

        $this->checkWorkoutKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        return Workout::find($content['id']);
    }


}