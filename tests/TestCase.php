<?php

namespace Tests;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $validationErrorMessage = 'The given data was invalid.';

    /**
     * Make an API call
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $headers = $this->transformHeadersToServerVars([
            'Accept' => 'application/json'
        ]);
        $server = array_merge($server, $headers);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     *
     * @return mixed
     */
    public function logInUser($id = 1)
    {
        $user = User::find($id);
        $this->be($user);
        $this->user = $user;
        $this->actingAs($user, 'api');
    }

    /**
     *
     * @param $content
     */
    protected function checkValidationError($content)
    {
        $this->assertEquals($this->validationErrorMessage, $content['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content['status']);
    }

    /**
     *
     * @param $exercise
     */
    protected function checkExerciseKeysExist($exercise)
    {
//        dd($exercise);
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('description', $exercise);
        $this->assertArrayHasKey('priority', $exercise);
    }

    /**
     *
     * @param $workout
     */
    protected function checkWorkoutKeysExist($workout)
    {
        $this->assertArrayHasKey('id', $workout);
        $this->assertArrayHasKey('name', $workout);
    }

    /**
     *
     * @param $workoutGroup
     */
    protected function checkWorkoutGroupKeysExist($workoutGroup)
    {
        $this->assertArrayHasKey('id', $workoutGroup);
        $this->assertArrayHasKey('order', $workoutGroup);
    }

    /**
     *
     * @param $entry
     */
    protected function checkExerciseEntryKeysExist($entry)
    {
        $this->checkExerciseKeysExist($entry['exercise']['data']);
        $this->checkUnitKeysExist($entry['unit']['data']);

        $this->assertArrayHasKey('id', $entry);
        $this->assertArrayHasKey('sets', $entry);
        $this->assertArrayHasKey('total', $entry);
        $this->assertArrayHasKey('quantity', $entry);
        $this->assertArrayHasKey('date', $entry);
        $this->assertArrayHasKey('daysAgo', $entry);
        $this->assertArrayHasKey('createdAt', $entry);

        $this->assertTrue(is_string($entry['createdAt']));
        //Test 'createdAt' is in the correct format
        /**
         * @VP:
         * Is there a nicer way to test this? Like $this->assertDateFormat()?
         */
        Carbon::createFromFormat('h:ia', $entry['createdAt']);
    }

    /**
     *
     * @param $unit
     */
    protected function checkUnitKeysExist($unit)
    {
        $this->assertArrayHasKey('id', $unit);
        $this->assertArrayHasKey('name', $unit);
        $this->assertArrayNotHasKey('created_at', $unit);
    }

    /**
     *
     * @param $session
     */
    protected function checkSessionKeysExist($session)
    {
        $this->assertArrayHasKey('id', $session);
        $this->assertArrayHasKey('name', $session);
        $this->assertArrayHasKey('created_at', $session);
    }

    /**
     *
     * @param $pagination
     */
    protected function checkPaginationKeysExist($pagination)
    {
        $this->assertArrayHasKey('per_page', $pagination);
        $this->assertArrayHasKey('current_page', $pagination);
        $this->assertArrayHasKey('next_page_url', $pagination);
        $this->assertArrayHasKey('prev_page_url', $pagination);
        $this->assertArrayHasKey('from', $pagination);
        $this->assertArrayHasKey('to', $pagination);
    }

    /**
     *
     * @param $exercise
     */
    protected function checkExerciseSessionKeysExist($exercise)
    {
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('exercise_id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('level', $exercise);
        $this->assertArrayHasKey('quantity', $exercise);
        $this->assertArrayHasKey('complete', $exercise);
        $this->checkUnitKeysExist($exercise['unit']['data']);
    }

    /**
     *
     * @param $response
     * @return mixed
     */
    protected function getContent($response)
    {
        return json_decode($response->getContent(), true);
    }
}
