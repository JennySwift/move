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
            $this->assertContains('p', strtolower($exercise['name']));
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

        $this->addExercise($exercise);
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
     */
    public function it_can_show_the_session_history_for_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . '/' . $exercise->id . '?include=sessions');
        $content = $this->getContent($response);
//        dd($content);

        $this->checkSessionKeysExist($content['data'][0]);
        $this->checkExerciseSessionKeysExist($content['data'][0]['exercises']['data'][0]);
        $this->checkPaginationKeysExist($content['pagination']);

        foreach ($content['data'] as $session) {
            foreach ($session['exercises']['data'] as $tempExercise) {
                $this->assertEquals($exercise->id, $tempExercise['exercise_id']);
            }
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEquals(1, $content['pagination']['current_page']);

    }

    /**
     * @test
     */
    public function it_can_show_the_session_history_for_an_exercise_for_page_two()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . '/' . $exercise->id . '?include=sessions&page=2');
        $content = $this->getContent($response);
//        dd($content);

        $this->checkSessionKeysExist($content['data'][0]);
        $this->checkExerciseSessionKeysExist($content['data'][0]['exercises']['data'][0]);
        $this->checkPaginationKeysExist($content['pagination']);

        foreach ($content['data'] as $session) {
            foreach ($session['exercises']['data'] as $tempExercise) {
                $this->assertEquals($exercise->id, $tempExercise['exercise_id']);
            }
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEquals(2, $content['pagination']['current_page']);

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

    /**
     * @test
     */
    public function it_cannot_create_a_exercise_with_the_same_name_for_the_same_user()
    {
        $this->logInUser();

        $existingExercise = $this->user->exercises->first();

        $data = $this->setRequiredFields($existingExercise);

        $response = $this->apiCall('POST', $this->url, $data);
        $content = $this->getContent($response);
//            dd($content);

        $this->checkValidationError($content);
    }

    /**
     * Todo: this test fails
     * @test
     * @return void
     */
    public function it_can_create_a_exercise_with_the_same_name_for_different_users()
    {
        $this->markTestIncomplete();
        $this->logInUser(1);
        $existingExerciseForAnotherUser = Exercise::where('user_id', 1)->first();

        $this->logInUser(2);
        $this->assertEquals(2, $this->user->id);
        $this->assertEquals(2, Auth::user()->id);



        $data = $this->setRequiredFields($existingExerciseForAnotherUser);

        $response = $this->apiCall('POST', $this->url, $data);
        $content = $this->getContent($response);
        dd($content);
        $this->assertEquals(Response::HTTP_OK, $content['status']);
    }

    /**
     *
     * @param Exercise $exercise
     * @return array
     */
    private function setRequiredFields(Exercise $exercise)
    {
        return [
            'name' => $exercise->name,
            'priority' => 1
        ];
    }

    /**
     *
     * @param $exercise
     */
    private function addExercise($exercise)
    {
        $response = $this->call('POST', $this->url, $exercise);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals($exercise['name'], $content['name']);
        $this->assertEquals($exercise['description'], $content['description']);
        $this->assertEquals($exercise['priority'], $content['priority']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
