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

    private $url = '/api/exercises';

    /**
     * @test
     * @return void
     */
    public function it_lists_all_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', $this->url);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_autocomplete_the_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', $this->url . '?typing=p');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);


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
            'priority' => 2
        ];

        $response = $this->call('POST', $this->url, $exercise);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals('kangaroo', $content['name']);
        $this->assertEquals('koala', $content['description']);
        $this->assertEquals(2, $content['priority']);

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

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_can_show_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . '/' . $exercise->id);
        $content = $this->getContent($response);

        $this->checkExerciseKeysExist($content);

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

        $response = $this->call('PUT', $this->url . '/' . $exercise->id, [
            'name' => 'numbat',
            'description' => 'frog',
            'priority' => 9,
        ]);
//        dd($response);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('frog', $content['description']);
        $this->assertEquals(9, $content['priority']);

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
        $url = $this->url . '/' . $exercise->id;

        $response = $this->call('DELETE', $url);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $url);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}
