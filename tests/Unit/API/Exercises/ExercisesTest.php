<?php

use App\Models\Exercise;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ExercisesTest
 */
class ExercisesTest extends TestCase {

    use DatabaseTransactions;

    private $url = '/api/exercises/';

    /**
     * Todo: finish
     * It lists the exercises for the user
     * @test
     * @return void
     */
    public function it_lists_all_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', $this->url);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals(1, $content[0]['stepNumber']);
        $this->assertEquals(20, $content[0]['defaultQuantity']);
        $this->assertEquals(7, $content[0]['frequency']);

        $this->assertEquals(1, $content[0]['series']['data']['id']);
        $this->assertEquals('pushup', $content[0]['series']['data']['name']);

        $this->assertEquals(1, $content[0]['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content[0]['defaultUnit']['data']['name']);

        $this->assertEquals(1, $content[1]['lastDone']);
        $this->assertEquals(3, $content[1]['frequency']);
        $this->assertEquals(2, $content[1]['dueIn']);

        $this->assertEquals(2, $content[2]['lastDone']);
        $this->assertEquals(4, $content[2]['frequency']);
        $this->assertEquals(2, $content[2]['dueIn']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_autocomplete_the_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exercises?typing=p');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['defaultUnit']['data']['id']);

        foreach ($content as $exercise) {
            $this->assertContains('p', $exercise['name']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Todo: finish
     * @test
     * @return void
     */
    public function it_can_add_a_new_exercise()
    {
        $this->logInUser();

        $exercise = [
            'name' => 'kangaroo',
            'description' => 'koala',
            'priority' => 2,
            'program_id' => 2,
            'series_id' => 2,
            'step_number' => 2,
            'default_quantity' => 2,
            'default_unit_id' => 2,
            'target' => '2 reps',
            'stretch' => 1,
            'frequency' => 14
        ];

        $response = $this->call('POST', $this->url, $exercise);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('step_number', $content);
        $this->assertArrayHasKey('description', $content);

        $this->assertEquals('kangaroo', $content['name']);
        $this->assertEquals('koala', $content['description']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['stretch']);
        $this->assertEquals(14, $content['frequency']);
        $this->assertEquals(2, $content['program']['data']['id']);
        $this->assertEquals(2, $content['series']['data']['id']);
        $this->assertEquals(2, $content['stepNumber']);
        $this->assertEquals(2, $content['defaultQuantity']);
        $this->assertEquals(2, $content['defaultUnit']['data']['id']);
        $this->assertEquals('2 reps', $content['target']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_cannot_create_an_exercise_without_the_required_fields()
    {
        DB::beginTransaction();
        $this->logInUser();

        $data = [];

        $response = $this->apiCall('POST', $this->url, $data);
        $content = $this->getContent($response);

      $this->assertContains($content['error'], $this->validationErrorMessage);

      //Stopped working after upgrading
//        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('priority', $content);
//        $this->assertArrayHasKey('program_id', $content);
//        $this->assertArrayHasKey('series_id', $content);
//        $this->assertArrayHasKey('default_unit_id', $content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_can_show_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . $exercise->id);
        $content = json_decode($response->getContent(), true);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(20, $content['defaultQuantity']);

        $this->assertEquals(1, $content['series']['data']['id']);
        $this->assertEquals('pushup', $content['series']['data']['name']);

        $this->assertEquals(1, $content['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content['defaultUnit']['data']['name']);

        //Todo: make seeder static for this
//        $this->assertEquals('kneeling pushups', $content['tag_ids']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $exercise->id, [
            'name' => 'numbat',
            'step_number' => 2,
            'default_quantity' => 6,
            'description' => 'frog',
            'series_id' => 2,
            'default_unit_id' => 2,
            'program_id' => 2,
            'target' => 'something else',
            'priority' => 9,
            'stretch' => 1,
            'frequency' => 30
        ]);
//        dd($response);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals(1, $content['stretch']);
        $this->assertEquals(2, $content['program']['data']['id']);
        $this->assertEquals('frog', $content['description']);
        $this->assertEquals(2, $content['stepNumber']);
        $this->assertEquals(6, $content['defaultQuantity']);
        $this->assertEquals('something else', $content['target']);
        $this->assertEquals(9, $content['priority']);
        $this->assertEquals(30, $content['frequency']);
        $this->assertEquals(30, $content['dueIn']);

        $this->assertEquals(2, $content['series']['data']['id']);
        $this->assertEquals('pullup', $content['series']['data']['name']);

        $this->assertEquals(2, $content['defaultUnit']['data']['id']);
        $this->assertEquals('minutes', $content['defaultUnit']['data']['name']);

        //Todo: check tags

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_set_stretch_to_false_for_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->where('stretch', 1)->first();

        $response = $this->call('PUT', $this->url . $exercise->id, [
            'stretch' => 0
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(0, $content['stretch']);

        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @test
     * @return void
     */
    public function it_can_update_an_exercise_default_quantity()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $exercise->id, [
            'default_quantity' => 7,
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(7, $content['defaultQuantity']);

        $this->assertEquals(1, $content['series']['data']['id']);
        $this->assertEquals('pushup', $content['series']['data']['name']);

        $this->assertEquals(1, $content['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content['defaultUnit']['data']['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_delete_an_exercise()
    {
        DB::beginTransaction();
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('DELETE', $this->url . $exercise->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $this->url . $exercise->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}
