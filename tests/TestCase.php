<?php

use App\User;
use Carbon\Carbon;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

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
    }

    /**
     *
     * @param $exercise
     */
    protected function checkExerciseKeysExist($exercise)
    {
        $this->checkBasicExerciseKeysExist($exercise);

        $this->assertArrayHasKey('series', $exercise);
        $this->assertArrayHasKey('defaultQuantity', $exercise);
        $this->assertArrayHasKey('program', $exercise);
        $this->assertArrayHasKey('lastDone', $exercise);
        $this->assertArrayHasKey('priority', $exercise);
        $this->assertArrayHasKey('target', $exercise);
        $this->assertArrayHasKey('stretch', $exercise);
        $this->assertArrayHasKey('frequency', $exercise);
        $this->assertArrayHasKey('dueIn', $exercise);
    }

    /**
     *
     * @param $exercise
     */
    private function checkBasicExerciseKeysExist($exercise)
    {
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('description', $exercise);
        $this->assertArrayHasKey('stepNumber', $exercise);
        $this->assertArrayHasKey('defaultUnit', $exercise);
    }

    /**
     *
     * @param $program
     */
    public function checkProgramKeysExist($program)
    {
        $this->assertArrayHasKey('id', $program);
        $this->assertArrayHasKey('name', $program);
    }

    /**
     *
     * @param $entry
     */
    protected function checkExerciseEntryKeysExist($entry)
    {
        $this->checkExerciseKeysExist($entry['exercise']['data']);
        $this->checkExerciseUnitKeysExist($entry['unit']);

        $this->assertArrayHasKey('exercise', $entry);

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
//        $this->assertArrayHasKey('workout_ids', $series);
        $this->assertArrayHasKey('lastDone', $series);
    }

    /**
     *
     * @param $unit
     */
    protected function checkExerciseUnitKeysExist($unit)
    {
        $this->assertArrayHasKey('id', $unit);
        $this->assertArrayHasKey('name', $unit);
        $this->assertArrayNotHasKey('created_at', $unit);
    }
}
