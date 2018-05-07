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
     * @test
     */
    public function it_can_delete_an_exercise_unit()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = $this->createUnit();

        $response = $this->call('DELETE', $this->url . $unit->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', $this->url . $unit->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_cannot_delete_an_exercise_unit_that_is_in_use()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = Unit::first();

        $response = $this->call('DELETE', $this->url . $unit->id);
        $content = $this->getContent($response);

        $this->assertArrayHasKey('error', $content);

        $this->assertEquals('Unit could not be deleted. It is in use.', $content['error']);
        $this->assertEquals(400, $response->getStatusCode());

        DB::rollBack();
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
