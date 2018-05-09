<?php

namespace Tests\Unit;

use App\Models\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class SessionsTest
 */
class SessionsTest extends TestCase
{
    use DatabaseTransactions;

    private $url = '/api/sessions/';

    /**
     * @test
     */
    public function it_gets_the_sessions()
    {
        $this->logInUser();
        $response = $this->call('GET', $this->url);
        $content = $this->getContent($response);
//      dd($content);

        $this->checkSessionKeysExist($content['data'][0]);
        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_gets_the_sessions_on_page_2()
    {
        $this->logInUser();
        $response = $this->call('GET', $this->url . '?page=2');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkSessionKeysExist($content['data'][0]);
        $this->checkPaginationKeysExist($content['pagination']);
        $this->assertEquals(2, $content['pagination']['current_page']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_create_a_session()
    {
        $this->logInUser();

        $session = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', $this->url, $session);
        $content = $this->getContent($response);
//         dd($content);

        $this->checkSessionKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_a_session_with_its_exercises()
    {
        $this->logInUser();

        $session = Session::forCurrentUser()->first();

        $response = $this->call('GET', $this->url . $session->id . '?include=exercises');
        $content = $this->getContent($response);
//        dd($content);

        $this->checkSessionKeysExist($content);

        $this->assertArrayHasKey('exercises', $content);
        $this->checkExerciseSessionKeysExist($content['exercises']['data'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_update_the_exercises_for_a_session()
    {
        $this->logInUser();

        $session = Session::forCurrentUser()->first();

        $this->assertEquals(1, $session->exercises[0]->pivot->complete);
        $this->assertEquals(0, $session->exercises[1]['complete']);

        $response = $this->call('PUT', $this->url . $session->id . '?include=exercises', [
            'name' => 'numbat',
            'exercises' => [
                [
                    'exercise_id' => 1,
                    'level' => 52,
                    'quantity' => 60,
                    'unit_id' => 1,
                    'complete' => 0
                ],
                [
                    'exercise_id' => 6,
                    'level' => 15,
                    'quantity' => 140,
                    'unit_id' => 2,
                    'complete' => 1
                ],
                [
                    'exercise_id' => 6,
                    'level' => 15,
                    'quantity' => 140,
                    'unit_id' => 2,
                    'complete' => 1
                ]
            ]
        ]);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkSessionKeysExist($content);
        $this->assertArrayHasKey('exercises', $content);
        $exercises = $content['exercises']['data'];
        $this->checkExerciseSessionKeysExist($exercises[0]);

        //Check the exercises are as expected
        $this->assertEquals(1, $exercises[0]['exercise_id']);
        $this->assertEquals(52, $exercises[0]['level']);
        $this->assertEquals(60, $exercises[0]['quantity']);
        $this->assertEquals(0, $exercises[0]['complete']);
        $this->assertEquals(1, $exercises[0]['unit']['data']['id']);

        $this->assertEquals(6, $exercises[1]['exercise_id']);
        $this->assertEquals(15, $exercises[1]['level']);
        $this->assertEquals(140, $exercises[1]['quantity']);
        $this->assertEquals(1, $exercises[1]['complete']);
        $this->assertEquals(2, $exercises[1]['unit']['data']['id']);

        $this->assertEquals(6, $exercises[2]['exercise_id']);
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
    public function it_can_delete_a_session()
    {
        $this->logInUser();

        $session = Session::forCurrentUser()->first();
        $url = $this->url . $session->id;

        $response = $this->call('DELETE', $url);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $url);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     *
     * @param $pagination
     */
    private function checkPaginationKeysExist($pagination)
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
    private function checkExerciseSessionKeysExist($exercise)
    {
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('exercise_id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('level', $exercise);
        $this->assertArrayHasKey('quantity', $exercise);
        $this->assertArrayHasKey('complete', $exercise);
        $this->checkUnitKeysExist($exercise['unit']['data']);
    }

}