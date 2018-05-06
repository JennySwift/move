<?php

use Illuminate\Http\Response;
use Tests\TestCase;

class PagesTest extends TestCase {

    /**
     * @test
     * @return void
     */
    public function it_redirects_the_user_if_not_authenticated()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirection());
        $this->assertEquals($this->baseUrl . '/login', $response->headers->get('Location'));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercises_page()
    {
        $this->logInUser();

        $response = $this->call('GET', '/#exercises');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }
}
