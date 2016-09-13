<?php

use App\Models\Exercise;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExerciseUnauthorizedTest
 */
class ExerciseUnauthorizedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_cannot_show_an_exercise_that_belongs_to_another_user()
    {
        $this->logInUser();

        $exercise = Exercise::where('user_id', 2)->first();

        $response = $this->call('GET', '/api/exercises/' . $exercise->id);
        $content = json_decode($response->getContent(), true);
//            dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_cannot_update_an_exercise_that_does_not_belong_to_the_current_user()
    {
        $this->logInUser();

        $exercise = Exercise::where('user_id', 2)->first();

        $response = $this->call('PUT', '/api/exercises/' . $exercise->id);
        //        dd($response);
        $content = json_decode($response->getContent(), true);
        //        dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_cannot_delete_an_exercise_that_belongs_to_another_user()
    {
        $this->logInUser();

        $this->assertEquals(1, $this->user->id);
        $exercise = Exercise::where('user_id', 2)->first();

        $response = $this->call('DELETE', '/api/exercises/' . $exercise->id);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        DB::rollBack();
    }
}