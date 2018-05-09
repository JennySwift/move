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
      dd($content);

        $this->checkSessionKeysExist($content[0]);

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

}