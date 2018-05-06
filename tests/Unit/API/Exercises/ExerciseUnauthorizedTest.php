<?php

use App\Models\Exercise;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ExerciseUnauthorizedTest
 */
class ExerciseUnauthorizedTest extends TestCase
{
    use DatabaseTransactions;

    private $errorMessage = 'Exercise not found.';

    /**
     * @test
     */
    public function it_cannot_show_an_exercise_that_belongs_to_another_user()
    {
        $this->logInUser(1);
        $this->assertEquals(1, $this->user->id);

        $exercise = Exercise::where('user_id', 2)->first();

        $response = $this->call('GET', '/api/exercises/' . $exercise->id);
        $content = json_decode($response->getContent(), true);
//            dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains($this->errorMessage, $content['error']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_cannot_update_an_exercise_that_does_not_belong_to_the_current_user()
    {
        $this->logInUser(1);
        $this->assertEquals(1, $this->user->id);

        $exercise = Exercise::where('user_id', 2)->first();

        $response = $this->call('PUT', '/api/exercises/' . $exercise->id);
        //        dd($response);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains($this->errorMessage, $content['error']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
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

        $this->assertEquals($this->errorMessage, $content['error']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        DB::rollBack();
    }
}