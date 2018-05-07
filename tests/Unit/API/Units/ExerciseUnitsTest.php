<?php

use App\Models\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class ExerciseUnitsTest extends TestCase {

    use DatabaseTransactions;

    private $url = '/api/units/';

    /**
     * It lists the exercise units for the user
     * Todo: check only the units for the current user are displayed,
     * without adding the user_id field to the response
     * @test
     * @return void
     */
    public function it_lists_all_exercise_units()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', $this->url);
        $content = json_decode($response->getContent(), true);
//dd($content);
        $this->checkUnitKeysExist($content[0]);

        $this->assertEquals(2, $content[0]['id']);
        $this->assertEquals('minutes', $content[0]['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_unit()
    {
        $this->logInUser();

        $this->createUnit();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_unit()
    {
        $this->logInUser();

        $unit = Unit::forCurrentUser()->first();

        $response = $this->call('PUT', $this->url . $unit->id, [
            'name' => 'numbat'
        ]);
        $content = $this->getContent($response);
//dd($content);
        $this->checkUnitKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     *
     */
    private function createUnit()
    {
        $unit = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', $this->url, $unit);
        $content = $this->getContent($response);

        $this->assertContains($unit['name'], $content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        return Unit::find($content['id']);
    }
}
