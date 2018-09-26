<?php

namespace Tests\Unit;

use App\Models\Workout;
use App\Models\WorkoutGroup;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class WorkoutGroupsTest
 */
class WorkoutGroupsTest extends TestCase
{
    use DatabaseTransactions;

    private $url = '/api/workoutGroups/';

    /**
     * @test
     */
    public function it_can_move_a_group_from_position_2_to_1()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $workoutGroups = $workout->groups()->get();

        foreach ($workoutGroups as $index => $group) {
            $this->assertEquals($index+1, $group->order);
        }

        //Change group with order 2 to order 1
        $response = $this->call('PUT', $this->url . 'reorder', [
            'old_position' => 2,
            'new_position' => 1,
            'workout_id' => $workout->id
        ]);

        $workoutGroups = $workout->groups()->get();
        $this->assertEquals(1, $workoutGroups[1]->order);
        $this->assertEquals(2, $workoutGroups[0]->order);
        $this->assertEquals(3, $workoutGroups[2]->order);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_move_a_group_from_position_8_to_3()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $workoutGroups = $workout->groups()->get();

        //Check the current positions are as expected
        foreach ($workoutGroups as $index => $group) {
            $this->assertEquals($index+1, $group->order);
        }

        //Change group with order 8 to order 3
        $response = $this->call('PUT', $this->url . 'reorder', [
            'old_position' => 8,
            'new_position' => 3,
            'workout_id' => $workout->id
        ]);

        $workoutGroups = $workout->groups()->get();
        $this->assertEquals(1, $workoutGroups[0]->order);
        $this->assertEquals(2, $workoutGroups[1]->order);
        $this->assertEquals(4, $workoutGroups[2]->order);
        $this->assertEquals(5, $workoutGroups[3]->order);
        $this->assertEquals(6, $workoutGroups[4]->order);
        $this->assertEquals(7, $workoutGroups[5]->order);
        $this->assertEquals(8, $workoutGroups[6]->order);
        $this->assertEquals(3, $workoutGroups[7]->order);
        $this->assertEquals(9, $workoutGroups[8]->order);
        $this->assertEquals(10, $workoutGroups[9]->order);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_move_a_group_from_position_3_to_8()
    {
        $this->logInUser();

        $workout = Workout::forCurrentUser()->first();
        $workoutGroups = $workout->groups()->get();

        //Check the current positions are as expected
        foreach ($workoutGroups as $index => $group) {
            $this->assertEquals($index+1, $group->order);
        }

        //Change group with order 3 to order 8
        $response = $this->call('PUT', $this->url . 'reorder', [
            'old_position' => 3,
            'new_position' => 8,
            'workout_id' => $workout->id
        ]);

        $workoutGroups = $workout->groups()->get();
        $this->assertEquals(1, $workoutGroups[0]->order);
        $this->assertEquals(2, $workoutGroups[1]->order);
        $this->assertEquals(8, $workoutGroups[2]->order);
        $this->assertEquals(3, $workoutGroups[3]->order);
        $this->assertEquals(4, $workoutGroups[4]->order);
        $this->assertEquals(5, $workoutGroups[5]->order);
        $this->assertEquals(6, $workoutGroups[6]->order);
        $this->assertEquals(7, $workoutGroups[7]->order);
        $this->assertEquals(9, $workoutGroups[8]->order);
        $this->assertEquals(10, $workoutGroups[9]->order);

        $this->assertEquals(200, $response->getStatusCode());
    }
}