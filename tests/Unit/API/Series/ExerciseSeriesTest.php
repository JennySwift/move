<?php

use App\Models\Exercise;
use App\Models\Series;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Todo
 * Class ExerciseSeriesTest
 */
class ExerciseSeriesTest extends TestCase {

    use DatabaseTransactions;

    private $url = '/api/series/';

    /**
     * @test
     */
    public function it_can_show_the_exercises_in_a_series()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', $this->url . '1?include=exercises');
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('exercises', $content);
//        dd($content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_list_the_series()
    {
        $this->logInUser();

        $response = $this->call('GET', $this->url);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkSeriesKeysExist($content[0]);

        $this->assertEquals('flexibility', $content[0]['name']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     */
    public function it_can_show_a_series()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . $series->id);
        $content = json_decode($response->getContent(), true);

        $this->checkSeriesKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('pushup', $content['name']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_series()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $this->assertEquals("#FF530D", $series->color);

        $response = $this->call('PUT', $this->url . $series->id, [
            'name' => 'numbat',
            'priority' => 8,
            'color' => 'black'
        ]);

        $content = $this->getContent($response);
        $this->checkSeriesKeysExist($content);

        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('8', $content['priority']);
        $this->assertEquals('black', $content['color']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_series_including_its_tags()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $series->id, [
            'name' => 'numbat'
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkSeriesKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_lists_a_series()
    {
//        $this->logInUser();
//
//        $response = $this->apiCall('GET', '/api/exercises');
//        $content = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('id', $content[0]);
//        $this->assertArrayHasKey('name', $content[0]);
//        $this->assertArrayHasKey('step_number', $content[0]);
//        $this->assertArrayHasKey('description', $content[0]);
//
//        $this->assertEquals('kneeling pushups', $content[0]['name']);
//        $this->assertEquals('http://localhost/api/exercises/1', $content[0]['path']);
//        $this->assertEquals('5.00', $content[0]['default_quantity']);
//
//        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_cannot_add_a_new_series_without_the_required_fields()
    {
        $this->logInUser();

        $series = [];

        $response = $this->apiCall('POST', $this->url, $series);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertContains($content['error'], $this->validationErrorMessage);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_delete_a_series()
    {
        DB::beginTransaction();
        $this->logInUser();

        $series = $this->createSeries();

        $response = $this->call('DELETE', $this->url . $series->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $this->url . $series->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_cannot_delete_a_series_that_is_in_use()
    {
        DB::beginTransaction();
        $this->logInUser();

        $series = Series::first();

        $response = $this->call('DELETE', $this->url . $series->id);
        $content = $this->getContent($response);

        $this->assertArrayHasKey('error', $content);

        $this->assertEquals('Series could not be deleted. It is in use.', $content['error']);
        $this->assertEquals(400, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_series()
    {
        $this->logInUser();

        $series = $this->createSeries();
    }

    /**
     *
     * @return Series
     */
    private function createSeries()
    {
        $series = [
            'name' => 'kangaroo',
            'priority' => 2,
            'color' => '#FF530D'
        ];

        $response = $this->call('POST', $this->url, $series);
        $content = json_decode($response->getContent(), true);

        $this->checkSeriesKeysExist($content);

        $this->assertEquals('kangaroo', $content['name']);
        $this->assertEquals('2', $content['priority']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        return Series::find($content['id']);
    }
}
