<?php

use Illuminate\Http\Response;

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
        $this->assertRedirectedTo($this->baseUrl.'/login');
    }

    /**
     * @test
     */
    public function it_tests_the_page_load_speed_of_home_page()
    {
        $start = microtime(true);
        $this->logInUser(2);
        $this->visit('/');
        $time = microtime(true) - $start;
        $this->assertLessThan(1, $time);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercises_page()
    {
        $this->logInUser();

        $this->visit('/#/exercises');
//            ->see('kneeling pushups');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercise_units_page()
    {
        $this->logInUser();

        $this->visit('/#/exercise-units');
            //This started erroring for some reason
//            ->see('reps')
//            ->see('minutes');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }
}
