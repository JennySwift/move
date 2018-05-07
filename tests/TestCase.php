<?php

namespace Tests;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $validationErrorMessage = 'The given data failed to pass validation.';

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
     * @param $exercise
     */
    protected function checkExerciseKeysExist($exercise)
    {
//        dd($exercise);
        $this->checkSeriesKeysExist($exercise['series']['data']);
        $this->checkUnitKeysExist($exercise['defaultUnit']['data']);

        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('description', $exercise);
        $this->assertArrayHasKey('stepNumber', $exercise);

        $this->assertArrayHasKey('defaultQuantity', $exercise);
        $this->assertArrayHasKey('lastDone', $exercise);
        $this->assertArrayHasKey('priority', $exercise);
        $this->assertArrayHasKey('frequency', $exercise);
        $this->assertArrayHasKey('dueIn', $exercise);
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
     * @param $series
     */
    protected function checkSeriesKeysExist($series)
    {
        $this->assertArrayHasKey('id', $series);
        $this->assertArrayHasKey('name', $series);
        $this->assertArrayHasKey('priority', $series);
        $this->assertArrayHasKey('lastDone', $series);
        $this->assertArrayHasKey('color', $series);
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
     * @param $response
     * @return mixed
     */
    protected function getContent($response)
    {
        return json_decode($response->getContent(), true);
    }
}
