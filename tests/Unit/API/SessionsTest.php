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
        $this->checkUnitKeysExist($exercise['unit']['data']);
    }

}